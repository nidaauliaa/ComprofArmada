<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class News extends Model
{
    public const CATEGORIES = ['Event', 'News'];
    public const LANGS = ['id', 'en', 'zh'];

    protected $table = 'news';

    protected $fillable = [
        'slug', 'title', 'category', 'published_at', 'thumbnail_path',
        'blocks', 'is_active', 'created_by',
    ];

    protected $casts = [
        'title'        => 'array',
        'blocks'       => 'array',
        'published_at' => 'date',
        'is_active'    => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Berita yang boleh tampil di website. */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)->whereDate('published_at', '<=', now());
    }

    /** Ambil teks sesuai bahasa. Jika kosong, jatuh ke bahasa Indonesia. */
    public function tr(string $field = 'title', ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $value = $this->{$field} ?? [];

        return (string) (($value[$locale] ?? '') !== '' ? $value[$locale] : ($value['id'] ?? ''));
    }

    /** Teks dari larik {id,en,zh} sesuai bahasa aktif; kosong -> Bahasa Indonesia. */
    public function localized($value, ?string $locale = null): string
    {
        $value = is_array($value) ? $value : [];
        $locale = $locale ?: app()->getLocale();

        return (string) (($value[$locale] ?? '') !== '' ? $value[$locale] : ($value['id'] ?? ''));
    }

    /** URL file. Semua file (termasuk bawaan "images/...") ada di storage/app/public, dilayani lewat /storage. */
    public static function fileUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return asset('storage/' . $path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path && ! \App\Support\StorageImage::exists($this->thumbnail_path)) {
            // file unggahan belum ada di storage ini: pakai gambar bawaan sesuai judul
            return asset('storage/'.\App\Support\StorageImage::guess($this->slug));
        }

        return self::fileUrl($this->thumbnail_path);
    }

        public function dateLabel(): string
    {
        return $this->published_at
            ? $this->published_at->copy()->locale(app()->getLocale())->translatedFormat('d F Y')
            : '';
    }

        /** Ringkasan = paragraf pertama (bahasa ID), dipotong sesuai batas. */
    public function summary(int $limit = 200): string
    {
        foreach ($this->blocks ?? [] as $b) {
            if (($b['type'] ?? 'p') === 'p' && $this->localized($b['text'] ?? []) !== '') {
                return Str::limit($this->localized($b['text'] ?? []), $limit);
            }
        }

        return '';
    }

    /** Data ringkas untuk hero halaman Berita (dibaca JavaScript). */
    public function toCardArray(): array
    {
        return [
            'id'    => $this->id,
            'url'   => route('news.show', $this->slug),
            'title' => $this->tr('title'),
            'desc'  => $this->summary(220),
            'date'  => $this->dateLabel(),
            'tag'   => $this->category,
            'img'   => $this->thumbnail_url,
        ];
    }

    /** Data untuk mengisi form "Ubah berita" di sisi kanan (dibaca JavaScript). */
    public function toEditArray(): array
    {
        $empty = ['id' => '', 'en' => '', 'zh' => ''];

        return [
            'id'            => $this->id,
            'update_url'    => route('admin.news.update', $this),
            'title'         => array_merge($empty, $this->title ?? []),
            'category'      => $this->category,
            'published_at'  => $this->published_at?->format('Y-m-d'),
            'is_active'     => $this->is_active,
            'thumbnail_url' => $this->thumbnail_url,
            'blocks'        => collect($this->blocks ?? [])->map(function ($b) use ($empty) {
                if (($b['type'] ?? 'p') === 'i') {
                    return [
                        'type'      => 'i',
                        'image'     => $b['image'] ?? '',
                        'image_url' => self::fileUrl($b['image'] ?? null),
                        'caption'   => array_merge($empty, $b['caption'] ?? []),
                    ];
                }

                return ['type' => 'p', 'text' => array_merge($empty, $b['text'] ?? [])];
            })->values()->all(),
        ];
    }
}
