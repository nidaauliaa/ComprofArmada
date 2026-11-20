<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialPost;
use App\Services\OembedService;
use Illuminate\Http\Request;

/**
 * Instagram & Sosial Media (API Management).
 *
 * Marketing cukup menempelkan SALAH SATU dari:
 *   - link share (Instagram / Facebook / TikTok / YouTube),
 *   - kode embed / oEmbed (<iframe …>) dari tombol Embed platform,
 *   - JSON oEmbed.
 * Sistem mengubahnya menjadi JSON oEmbed yang aman, lalu menampilkannya di Home.
 * JSON hasilnya juga bisa diedit manual per konten (menu ⋯ → Edit JSON).
 */
class SocialPostController extends Controller
{
    public function index()
    {
        return view('admin.social.index', [
            'posts' => SocialPost::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request, OembedService $oembed)
    {
        $request->validate(['link' => 'required|string|max:30000'], [
            'link.required' => 'Tempel dulu link share, kode embed (oEmbed), atau JSON-nya.',
        ]);

        [$ok, $d, $err] = $oembed->convert($request->input('link'));
        if (! $ok) {
            return back()->withInput()->with('error', $err);
        }
        if (! $this->isInstagramReel($d)) {
            return back()->withInput()->with('error', 'Hanya link atau kode embed Instagram Reel yang diperbolehkan. Story, postingan biasa, profil, dan platform lain ditolak.');
        }
        if (SocialPost::query()->where('platform', $d['platform'])->where('external_id', $d['external_id'])->exists()) {
            return back()->with('error', 'Konten ini sudah ada di daftar.');
        }

        SocialPost::query()->create([
            'platform' => $d['platform'],
            'kind' => $d['kind'],
            'external_id' => $d['external_id'],
            'source_url' => $d['source_url'],
            'embed_url' => $d['embed_url'],
            'embed_width' => $d['embed_width'],
            'embed_height' => $d['embed_height'],
            'title' => $d['title'],
            'author' => $d['author'],
            'thumbnail_url' => $d['thumbnail_url'],
            'oembed' => $d['oembed'],
            'is_active' => true,
            'sort_order' => ((int) SocialPost::query()->min('sort_order')) - 1,
        ]);

        return redirect()->route('admin.social.index')->with('success', 'Berhasil! Konten sudah tampil di Home.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|distinct',
        ]);

        foreach ($data['ids'] as $index => $id) {
            SocialPost::query()->whereKey($id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['reordered' => true]);
    }

    /** Simpan hasil edit JSON manual. */
    public function update(Request $request, SocialPost $post, OembedService $oembed)
    {
        $request->validate(['json' => 'required|string|max:100000']);

        $fail = fn (string $msg) => back()->withInput()->with('error', $msg)->with('open_json', $post->id);

        $j = json_decode($request->input('json'), true);
        if (! is_array($j)) {
            return $fail('JSON tidak valid ('.json_last_error_msg().'). Periksa tanda kutip " dan koma, lalu simpan lagi.');
        }

        $platform = strtolower(trim((string) ($j['platform'] ?? $post->platform)));
        if ($platform !== 'instagram') {
            return $fail('"platform" hanya boleh "instagram". Halaman ini khusus Instagram Reel.');
        }

        $oe = isset($j['oembed']) && is_array($j['oembed']) ? $j['oembed'] : ($post->oembed ?: []);
        $embed = trim((string) ($j['embed_url'] ?? $post->embed_url));

        // Bila yang diedit adalah iframe di dalam "oembed.html" (embed_url dibiarkan), ambil alamat barunya.
        if ($embed === (string) $post->embed_url) {
            $html = (string) ($oe['html'] ?? $oe['iframe'] ?? '');
            if ($html !== '' && preg_match('~<iframe\b[^>]*?\ssrc\s*=\s*(["\'])(.*?)\1~is', $html, $m)) {
                $src = html_entity_decode(trim($m[2]), ENT_QUOTES | ENT_HTML5);
                if ($src !== '' && $src !== $embed) {
                    $embed = $src;
                }
            }
        }

        if (! OembedService::isAllowedEmbed($embed) || ! OembedService::isInstagramReelUrl($embed)) {
            return $fail('"embed_url" harus berupa alamat embed Instagram Reel (/reel/KODE/embed/). Story, post, dan platform lain ditolak.');
        }

        $source = trim((string) ($j['source_url'] ?? $post->source_url));
        if (! filter_var($source, FILTER_VALIDATE_URL) || ! preg_match('~^https?://~i', $source) || ! OembedService::isInstagramReelUrl($source)) {
            return $fail('"source_url" harus berupa link Instagram Reel yang lengkap.');
        }

        $thumb = $j['thumbnail_url'] ?? null;
        if ($thumb !== null && $thumb !== '' && (! is_string($thumb) || ! preg_match('~^https?://~i', $thumb) || ! filter_var($thumb, FILTER_VALIDATE_URL))) {
            return $fail('"thumbnail_url" harus berupa link gambar (https://…) atau dikosongkan.');
        }

        $width = max(200, min(750, (int) ($j['width'] ?? $oe['width'] ?? $post->embedWidth())));
        $height = max(150, min(1400, (int) ($j['height'] ?? $oe['height'] ?? $post->embedHeight())));

        $attrs = [
            'platform' => 'instagram',
            'kind' => 'reel',
            'title' => $this->str($j['title'] ?? null),
            'author' => $this->str($j['author'] ?? null),
            'source_url' => $source,
            'embed_url' => $embed,
            'embed_width' => $width,
            'embed_height' => $height,
            'thumbnail_url' => ($thumb === '' ? null : $thumb),
        ];

        // Alamat embed berubah -> kenali ulang konten (supaya pencegah duplikat tetap benar).
        if ($embed !== (string) $post->embed_url) {
            [$ok, $d] = $oembed->convert(OembedService::iframeHtml($embed, $width, $height));
            if ($ok && $this->isInstagramReel($d)) {
                $dup = SocialPost::query()->where('platform', $d['platform'])->where('external_id', $d['external_id'])
                    ->where('id', '!=', $post->id)->exists();
                if ($dup) {
                    return $fail('Konten dengan alamat embed ini sudah ada di daftar.');
                }
                $attrs['platform'] = $d['platform'];
                $attrs['external_id'] = $d['external_id'];
                $attrs['kind'] = 'reel';
            } elseif (! $ok || ! $this->isInstagramReel($d)) {
                return $fail('Konten Reel ini tidak dapat diproses. Pastikan alamat embed dan link sumber menuju Reel Instagram yang sama.');
            }
        }

        // Sinkronkan dokumen oEmbed dengan nilai terbaru; isi lain yang ditulis manual dipertahankan.
        $oe['html'] = OembedService::iframeHtml($embed, $width, $height, $attrs['title'] ?: ucfirst($attrs['platform']));
        $oe['embed_url'] = $embed;
        $oe['width'] = $width;
        $oe['height'] = $height;
        $oe['source'] = 'manual-json';
        $attrs['oembed'] = $oe;

        $post->update($attrs);

        return redirect()->route('admin.social.index')->with('success', 'JSON tersimpan. Tampilan di Home sudah diperbarui.');
    }

    public function toggle(SocialPost $post)
    {
        $post->update(['is_active' => ! $post->is_active]);

        return back()->with('success', $post->is_active ? 'Konten ditampilkan di web.' : 'Konten disembunyikan dari web.');
    }

    public function destroy(SocialPost $post)
    {
        $post->delete();

        return back()->with('success', 'Konten dihapus dari web.');
    }

    private function str($v): ?string
    {
        if (! is_string($v)) {
            return null;
        }
        $v = trim($v);

        return $v === '' ? null : mb_substr($v, 0, 250);
    }

    private function isInstagramReel(array $data): bool
    {
        return ($data['platform'] ?? null) === 'instagram'
            && ($data['kind'] ?? null) === 'reel'
            && OembedService::isInstagramReelUrl($data['source_url'] ?? null)
            && OembedService::isInstagramReelUrl($data['embed_url'] ?? null);
    }
}
