<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Category;
use App\Models\ModelGallery;
use App\Models\News;
use App\Models\Plant;
use App\Models\ProductModel;
use App\Models\SalesContact;
use App\Models\Setting;
use App\Models\SocialPost;
use App\Support\StorageImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Membuat JSON konten publik dari database admin.
 *
 * - Hanya konten yang tampil di web (aktif / sudah terbit). Draf dan data internal tidak ikut.
 * - Teks multi bahasa selalu berbentuk {"id": "...", "en": "...", "zh": "..."}.
 * - Gambar selalu berbentuk objek {path, url, size, modified, fallback} (lihat image()).
 * - Hasil di-cache; cache dibersihkan otomatis saat konten diubah (lihat AppServiceProvider).
 */
class ContentApi
{
    public const VERSION = '1';

    public const CACHE_KEY = 'content_api.v1.payload';

    /** Muatan lengkap (dari cache bila ada). */
    public static function payload(): array
    {
        $ttl = (int) config('content_api.cache_seconds', 300);
        if ($ttl <= 0) {
            return self::build();
        }

        try {
            return Cache::remember(self::CACHE_KEY, $ttl, fn () => self::build());
        } catch (\Throwable $e) {
            return self::build();
        }
    }

    public static function flush(): void
    {
        try {
            Cache::forget(self::CACHE_KEY);
        } catch (\Throwable $e) {
            // cache belum tersedia: abaikan
        }
    }

    /** Bangun JSON dari database. */
    public static function build(): array
    {
        $resources = [
            'settings' => self::safe(fn () => self::settings()),
            'banners' => self::safe(fn () => self::banners()),
            'categories' => self::safe(fn () => self::categories()),
            'products' => self::safe(fn () => self::products()),
            'news' => self::safe(fn () => self::news()),
            'plants' => self::safe(fn () => self::plants()),
            'social' => self::safe(fn () => self::social()),
            'sales_contacts' => self::safe(fn () => self::salesContacts()),
        ];

        $revisions = [];
        $counts = [];
        foreach ($resources as $name => $data) {
            $revisions[$name] = md5(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $counts[$name] = count($data);
        }

        return [
            'version' => self::VERSION,
            'base_url' => url('/'),
            'generated_at' => now()->toIso8601String(),
            // berubah hanya bila isi konten berubah (dipakai sebagai ETag)
            'revision' => md5(json_encode($revisions)),
            'revisions' => $revisions,
            'counts' => $counts,
            'resources' => $resources,
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Resource                                                          */
    /* ------------------------------------------------------------------ */

    private static function banners(): array
    {
        return Banner::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn (Banner $b) => [
                'id' => $b->id,
                'sort_order' => (int) $b->sort_order,
                'tag' => self::tr3($b->tag, $b->tag_en, $b->tag_zh),
                'title' => self::tr3($b->title, $b->title_en, $b->title_zh),
                'headline' => self::tr3($b->headline, $b->headline_en, $b->headline_zh),
                'description' => self::tr3($b->description, $b->description_en, $b->description_zh),
                'button_text' => self::tr3($b->button_text, $b->button_text_en, $b->button_text_zh),
                'button_link' => $b->button_link,
                'image' => self::image($b->image_path, $b->title),
            ])->values()->all();
    }

    private static function categories(): array
    {
        return Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn (Category $c) => [
                'id' => $c->id,
                'slug' => $c->slug,
                'sort_order' => (int) $c->sort_order,
                'name' => self::tr3($c->name, $c->name_en, $c->name_zh),
                'short_text' => $c->short_text,
                'color_bg' => $c->color_bg,
                'image' => self::image($c->posterPath(), $c->slug),
            ])->values()->all();
    }

    private static function products(): array
    {
        return ProductModel::query()->with(['category', 'galleries'])
            ->where('is_active', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')->orderBy('id')->get()
            ->map(function (ProductModel $m) {
                return [
                    'id' => $m->id,
                    'slug' => $m->slug,
                    'category_slug' => $m->category?->slug,
                    'sort_order' => (int) $m->sort_order,
                    'name' => $m->name,
                    'brand' => $m->brand,
                    'subtitle' => self::tr3($m->subtitle, $m->subtitle_en, $m->subtitle_zh),
                    'description' => self::tr3($m->description, $m->description_en, $m->description_zh),
                    'specs' => ['height' => $m->height, 'width' => $m->width, 'length' => $m->length],
                    'image' => self::image($m->image_path ?: $m->category?->posterPath(), $m->slug),
                    'gallery' => $m->galleries->sortBy('sort_order')->values()
                        ->map(fn (ModelGallery $g) => self::image($g->image_path, $m->slug))
                        ->filter()->values()->all(),
                ];
            })->values()->all();
    }

    private static function news(): array
    {
        return News::query()->published()->orderByDesc('published_at')->orderByDesc('id')->get()
            ->map(function (News $n) {
                $blocks = [];
                foreach ($n->blocks ?? [] as $b) {
                    if (($b['type'] ?? 'p') === 'i') {
                        $blocks[] = [
                            'type' => 'image',
                            'image' => self::image($b['image'] ?? null, $n->slug),
                            'caption' => self::arr3($b['caption'] ?? []),
                        ];
                    } else {
                        $blocks[] = ['type' => 'paragraph', 'text' => self::arr3($b['text'] ?? [])];
                    }
                }

                return [
                    'id' => $n->id,
                    'slug' => $n->slug,
                    'title' => self::arr3($n->title ?? []),
                    'category' => $n->category,
                    'published_at' => $n->published_at?->format('Y-m-d'),
                    'thumbnail' => self::image($n->thumbnail_path, $n->slug),
                    'blocks' => $blocks,
                ];
            })->values()->all();
    }

    private static function plants(): array
    {
        // plant nonaktif hanya ikut bila bertanda kantor pusat (dipakai halaman Kontak)
        return Plant::query()->where(fn ($q) => $q->where('is_active', true)->orWhere('is_hq', true))
            ->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn (Plant $p) => [
                'id' => $p->id,
                'sort_order' => (int) $p->sort_order,
                'is_hq' => (bool) $p->is_hq,
                'is_active' => (bool) $p->is_active,
                'name' => self::tr3($p->name, $p->name_en, $p->name_zh),
                'address' => self::tr3($p->address, $p->address_en, $p->address_zh),
                'phones' => $p->phoneList(),
                'email' => $p->email,
                'working_hours' => self::tr3($p->working_hours, $p->working_hours_en, $p->working_hours_zh),
                'maps_url' => $p->maps_url,
                'lat' => $p->lat,
                'lng' => $p->lng,
            ])->values()->all();
    }

    private static function social(): array
    {
        return SocialPost::query()->visible()->get()
            ->filter(fn (SocialPost $s) => $s->safeEmbedUrl())
            ->map(fn (SocialPost $s) => [
                'id' => $s->id,
                'platform' => $s->platform,
                'kind' => $s->kind,
                'title' => $s->title,
                'author' => $s->author,
                'source_url' => $s->source_url,
                'embed_url' => $s->safeEmbedUrl(),
                'width' => $s->embedWidth(),
                'height' => $s->embedHeight(),
                'thumbnail_url' => $s->thumbnail_url,
                'sort_order' => (int) $s->sort_order,
            ])->values()->all();
    }

    private static function salesContacts(): array
    {
        return SalesContact::query()->active()->ordered()->get()
            ->map(fn (SalesContact $s) => ['id' => $s->id, 'name' => $s->name, 'phone' => $s->phone])
            ->values()->all();
    }

    /** Pengaturan situs. Kunci yang mengandung password/secret/token/key tidak pernah ikut. */
    private static function settings(): array
    {
        $out = [];
        foreach (Setting::allCached() as $key => $value) {
            if (preg_match('/(password|secret|token|api_?key|private)/i', (string) $key)) {
                continue;
            }
            if (in_array($key, ['logo', 'favicon'], true)) {
                $out[$key] = self::image(is_string($value) ? $value : null, 'logo');

                continue;
            }
            if (is_string($value) && str_starts_with(ltrim($value), '{')) {
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    $out[$key] = self::arr3($decoded);

                    continue;
                }
            }
            $out[$key] = $value;
        }
        ksort($out);

        return $out;
    }

    /* ------------------------------------------------------------------ */
    /*  Bentuk seragam                                                    */
    /* ------------------------------------------------------------------ */

    /** {id,en,zh} dari tiga kolom. */
    public static function tr3(?string $id, ?string $en = null, ?string $zh = null): array
    {
        return ['id' => (string) $id, 'en' => (string) $en, 'zh' => (string) $zh];
    }

    /** {id,en,zh} dari larik yang mungkin tidak lengkap. */
    public static function arr3($value): array
    {
        $value = is_array($value) ? $value : [];

        return [
            'id' => (string) ($value['id'] ?? ''),
            'en' => (string) ($value['en'] ?? ''),
            'zh' => (string) ($value['zh'] ?? ''),
        ];
    }

    /**
     * Objek gambar:
     *   path     = lokasi relatif di storage (kunci untuk menyalin ke web)
     *   url      = alamat lengkap yang bisa diunduh
     *   size     = ukuran file (byte)
     *   modified = waktu file terakhir diubah (untuk mendeteksi gambar yang berganti)
     *   fallback = true bila file unggahan tidak ada di storage sehingga dipakai gambar bawaan
     */
    public static function image(?string $path, ?string $hint = null): ?array
    {
        if (! $path) {
            return null;
        }

        $disk = Storage::disk('public');
        $fallback = false;

        if (! $disk->exists($path)) {
            $path = StorageImage::guess($hint);
            $fallback = true;
            if (! $disk->exists($path)) {
                return null;
            }
        }

        return [
            'path' => $path,
            'url' => url('storage/'.ltrim($path, '/')),
            'size' => $disk->size($path),
            'modified' => Carbon::createFromTimestamp($disk->lastModified($path))->toIso8601String(),
            'fallback' => $fallback,
        ];
    }

    private static function safe(callable $fn): array
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            return []; // tabel belum dimigrasi
        }
    }
}
