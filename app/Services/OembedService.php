<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Mengubah masukan marketing menjadi data oEmbed (JSON) yang aman ditampilkan di web.
 *
 * Masukan yang diterima (otomatis dikenali):
 *   1. Link share biasa            : https://www.instagram.com/reel/XXXX/  |  facebook.com/...  |  tiktok.com/...  |  youtube.com/...
 *   2. Kode embed / oEmbed (HTML)  : <iframe src="https://www.facebook.com/plugins/video.php?..." ...></iframe>
 *                                    atau <blockquote class="instagram-media" data-instgrm-permalink="..."> / <blockquote class="tiktok-embed" cite="...">
 *   3. JSON oEmbed                 : {"html": "<iframe ...>", "title": "...", ...}
 *
 * Keamanan: HTML yang ditempel TIDAK pernah disimpan/ditampilkan mentah. Sistem hanya mengambil alamat embed (src)
 * dan ukurannya, memastikan alamatnya berasal dari domain resmi platform (ALLOWED_PREFIXES), lalu membangun ulang
 * iframe-nya sendiri. Script, atribut aneh, dan iframe ke situs lain dibuang.
 *
 * Konten harus PUBLIK. Tidak ada token/API key yang disimpan.
 */
class OembedService
{
    /** Hanya URL embed berawalan ini yang boleh dirender sebagai iframe. */
    public const ALLOWED_PREFIXES = [
        'https://www.instagram.com/',
        'https://www.facebook.com/plugins/',
        'https://www.tiktok.com/embed/',
        'https://www.tiktok.com/player/',
        'https://www.youtube.com/embed/',
        'https://www.youtube-nocookie.com/embed/',
        'https://www.linkedin.com/embed/',
    ];

    public const PLATFORMS = ['instagram', 'facebook', 'tiktok', 'youtube', 'linkedin'];

    /** Pemutar TikTok resmi (tiktok.com/player/v1/ID): hanya video, tanpa daftar "related videos", judul, atau info musik. */
    public static function tiktokPlayerUrl(string $id): string
    {
        return 'https://www.tiktok.com/player/v1/'.$id.'?'.http_build_query([
            'controls' => 1, 'progress_bar' => 1, 'play_button' => 1, 'volume_control' => 1, 'fullscreen_button' => 0,
            'timestamp' => 0, 'music_info' => 0, 'description' => 0, 'rel' => 0, 'loop' => 1,
            'native_context_menu' => 0, 'closed_caption' => 0,
        ]);
    }

    private const UA = 'Mozilla/5.0 (compatible; NewArmadaEmbed/1.0)';

    /** Return: [ok, data|null, error|null] */
    public function convert(string $input): array
    {
        $input = trim($input);
        if ($input === '') {
            return [false, null, 'Isi dulu link share, kode embed (oEmbed), atau JSON-nya.'];
        }

        // 3. JSON oEmbed
        if ($input[0] === '{') {
            return $this->fromJsonString($input);
        }

        // 2. Kode embed HTML
        if (str_contains($input, '<')) {
            return $this->fromHtml($input);
        }

        // 1. Link share biasa
        return $this->fromLink($input);
    }

    /* ------------------------------------------------------------------ */
    /*  JSON                                                              */
    /* ------------------------------------------------------------------ */

    private function fromJsonString(string $json): array
    {
        $j = json_decode($json, true);
        if (! is_array($j)) {
            return [false, null, 'JSON tidak valid: '.json_last_error_msg().'. Periksa tanda kutip dan koma.'];
        }

        return $this->fromJsonArray($j);
    }

    public function fromJsonArray(array $j): array
    {
        $html = (string) ($j['html'] ?? $j['iframe'] ?? '');
        $embedUrl = (string) ($j['embed_url'] ?? '');
        $source = (string) ($j['source_url'] ?? $j['url'] ?? $j['author_url'] ?? '');

        if ($html !== '') {
            [$ok, $d, $err] = $this->fromHtml($html);
            if ($ok) {
                $d = $this->overlay($d, $j);
                return [true, $d, null];
            }
            if ($embedUrl === '' && $source === '') {
                return [false, null, $err];
            }
        }

        if ($embedUrl !== '') {
            [$ok, $d, $err] = $this->fromEmbedSrc($embedUrl, $this->intOrNull($j['width'] ?? null), $this->intOrNull($j['height'] ?? null), $source ?: null);
            if (! $ok) {
                return [false, null, $err];
            }
            return [true, $this->overlay($d, $j), null];
        }

        if ($source !== '') {
            [$ok, $d, $err] = $this->fromLink($source);
            if (! $ok) {
                return [false, null, $err];
            }
            return [true, $this->overlay($d, $j), null];
        }

        return [false, null, 'JSON belum berisi "html" (iframe), "embed_url", atau "url" yang bisa ditampilkan.'];
    }

    /** Judul/penulis/thumbnail dari JSON menimpa hasil konversi (bila diisi). */
    private function overlay(array $d, array $j): array
    {
        foreach (['title', 'author_name' => 'author', 'thumbnail_url'] as $from => $to) {
            $from = is_int($from) ? $to : $from;
            if (! empty($j[$from]) && is_string($j[$from])) {
                $d[$to] = mb_substr($j[$from], 0, 250);
            }
        }
        if (! empty($j['author']) && is_string($j['author'])) {
            $d['author'] = mb_substr($j['author'], 0, 250);
        }
        $d['oembed']['source'] = 'json';

        return $d;
    }

    /* ------------------------------------------------------------------ */
    /*  HTML (iframe / blockquote)                                        */
    /* ------------------------------------------------------------------ */

    private function fromHtml(string $html): array
    {
        // a) <iframe src="...">  (ukuran dibaca dari atribut iframe, bukan dari isi src)
        if (preg_match('~<iframe\b[^>]*>~is', $html, $tag) && preg_match('~\ssrc\s*=\s*(["\'])(.*?)\1~is', $tag[0], $m)) {
            $src = html_entity_decode(trim($m[2]), ENT_QUOTES | ENT_HTML5);
            if (str_starts_with($src, '//')) {
                $src = 'https:'.$src;
            }
            $attrs = str_replace($m[0], ' ', $tag[0]);
            $w = preg_match('~\swidth\s*=\s*["\']?(\d{2,4})~i', $attrs, $mw) ? (int) $mw[1] : null;
            $h = preg_match('~\sheight\s*=\s*["\']?(\d{2,4})~i', $attrs, $mh) ? (int) $mh[1] : null;

            [$ok, $d, $err] = $this->fromEmbedSrc($src, $w, $h, null);
            if ($ok) {
                $d['oembed']['source'] = 'embed';
            }
            return [$ok, $d, $err];
        }

        // b) Instagram: <blockquote class="instagram-media" data-instgrm-permalink="...">
        if (preg_match('~data-instgrm-permalink\s*=\s*(["\'])(.*?)\1~is', $html, $m)) {
            return $this->fromLink(html_entity_decode($m[2], ENT_QUOTES | ENT_HTML5));
        }

        // c) TikTok: <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@akun/video/123" data-video-id="123">
        if (preg_match('~class\s*=\s*["\'][^"\']*tiktok-embed[^"\']*["\'][^>]*?\bcite\s*=\s*(["\'])(.*?)\1~is', $html, $m)
            || preg_match('~\bcite\s*=\s*(["\'])(https?://[^"\']*tiktok\.com[^"\']*)\1~is', $html, $m)) {
            return $this->fromLink(html_entity_decode($m[2], ENT_QUOTES | ENT_HTML5));
        }

        // d) Facebook: <div class="fb-post" data-href="..."> / <div class="fb-video" data-href="...">
        if (preg_match('~\bdata-href\s*=\s*(["\'])(.*?)\1~is', $html, $m)) {
            return $this->fromLink(html_entity_decode($m[2], ENT_QUOTES | ENT_HTML5));
        }

        // e) tidak ada iframe: ambil link pertama
        if (preg_match('~https?://[^\s"\'<>]+~i', $html, $m)) {
            return $this->fromLink(html_entity_decode($m[0], ENT_QUOTES | ENT_HTML5));
        }

        return [false, null, 'Kode embed tidak dikenali. Salin kode lengkap yang diawali <iframe …> dari tombol Embed / Sematkan, atau tempel link share biasa.'];
    }

    /** Dari alamat embed (src iframe). */
    private function fromEmbedSrc(string $src, ?int $w, ?int $h, ?string $source): array
    {
        $src = trim($src);
        $allowed = false;
        foreach (self::ALLOWED_PREFIXES as $prefix) {
            if (str_starts_with($src, $prefix)) {
                $allowed = true;
                break;
            }
        }
        if (! $allowed) {
            return [false, null, 'Alamat embed ini bukan dari Instagram, Facebook, TikTok, YouTube, atau LinkedIn, jadi tidak ditampilkan demi keamanan.'];
        }

        $p = parse_url($src);
        $host = strtolower($p['host'] ?? '');
        $path = $p['path'] ?? '';
        parse_str($p['query'] ?? '', $q);

        // ---- Facebook plugin: post.php / video.php?href=... ----
        if (str_ends_with($host, 'facebook.com')) {
            $href = (string) ($q['href'] ?? '');
            if ($href === '') {
                return [false, null, 'Kode embed Facebook ini tidak memuat alamat postingan (href).'];
            }
            $isVideo = str_contains($path, 'video.php');
            $width = $this->clamp($w ?? (int) ($q['width'] ?? 0) ?: 350, 200, 750);
            $height = $this->clamp($h ?? (int) ($q['height'] ?? 0) ?: ($isVideo ? 620 : 560), 200, 1400);
            // pakai ulang src asli (sudah terbukti jalan di Facebook), hanya tambahkan ukuran bila kosong
            return [true, $this->result('facebook', $isVideo ? 'video' : 'post', md5($href), $source ?: $href, $src, $width, $height, null, null, null), null];
        }

        // ---- Instagram: /p/CODE/embed/ , /reel/CODE/embed/ ----
        if (str_ends_with($host, 'instagram.com')) {
            if (! preg_match('~^/(p|reels?|tv)/([A-Za-z0-9_-]{5,})~', $path, $m)) {
                return [false, null, 'Alamat embed Instagram harus ke postingan atau reel (…/p/KODE/embed/ atau …/reel/KODE/embed/).'];
            }
            $seg = $m[1] === 'reels' ? 'reel' : $m[1];
            $kind = $seg === 'p' ? 'post' : 'reel';
            $clean = 'https://www.instagram.com/'.$seg.'/'.$m[2].'/';
            $width = $this->clamp($w ?? 350, 200, 750);
            $height = $this->clamp($h ?? ($kind === 'post' ? 640 : 700), 200, 1400);

            return [true, $this->result('instagram', $kind, $m[2], $source ?: $clean, $clean.'embed/', $width, $height, null, null, null), null];
        }

        // ---- TikTok: /embed/v2/ID ----
        if (str_ends_with($host, 'tiktok.com')) {
            if (! preg_match('~/(?:embed/(?:v\d/)?|player/v\d/)(\d{8,25})~', $path, $m)) {
                return [false, null, 'Alamat embed TikTok harus berbentuk https://www.tiktok.com/player/v1/ID_VIDEO atau https://www.tiktok.com/embed/v2/ID_VIDEO.'];
            }
            $width = 360;
            $height = 640;
            $embed = self::tiktokPlayerUrl($m[1]);
            $real = $source ? $this->tiktokOembed($source) : null;

            return [true, $this->result('tiktok', 'video', $m[1], $source ?: 'https://www.tiktok.com/video/'.$m[1], $embed, $width, $height,
                $real['title'] ?? null, $real['author_name'] ?? null, $real['thumbnail_url'] ?? null, $real), null];
        }

        // ---- YouTube: /embed/ID ----
        if (str_ends_with($host, 'youtube.com') || str_ends_with($host, 'youtube-nocookie.com')) {
            if (! preg_match('~^/embed/([A-Za-z0-9_-]{6,})~', $path, $m)) {
                return [false, null, 'Alamat embed YouTube harus berbentuk https://www.youtube.com/embed/ID_VIDEO.'];
            }
            $width = $this->clamp($w ?? 560, 200, 900);
            $height = $this->clamp($h ?? 315, 150, 1000);

            return [true, $this->result('youtube', 'video', $m[1], $source ?: 'https://www.youtube.com/watch?v='.$m[1],
                'https://www.youtube.com/embed/'.$m[1], $width, $height, null, null, null), null];
        }

        // ---- LinkedIn: /embed/feed/update/urn:li:share:123 ----
        if (str_ends_with($host, 'linkedin.com')) {
            $width = $this->clamp($w ?? 504, 200, 750);
            $height = $this->clamp($h ?? 600, 200, 1400);

            return [true, $this->result('linkedin', 'post', md5($src), $source ?: $src, $src, $width, $height, null, null, null), null];
        }

        return [false, null, 'Platform embed ini belum didukung.'];
    }

    /* ------------------------------------------------------------------ */
    /*  Link share biasa                                                  */
    /* ------------------------------------------------------------------ */

    private function fromLink(string $link): array
    {
        $link = trim($link);
        $p = parse_url($link);
        if (! $p || ! in_array(strtolower($p['scheme'] ?? ''), ['http', 'https'], true) || empty($p['host'])) {
            return [false, null, 'Link tidak valid. Tempel link lengkap yang diawali https:// , atau tempel kode embed (<iframe …>).'];
        }
        $host = strtolower(preg_replace('/^(www|m|mobile|web|l|lm)\./', '', $p['host']));

        if ($this->isHost($host, 'instagram.com') || $host === 'instagr.am') {
            return $this->instagram($p);
        }
        if ($this->isHost($host, 'facebook.com') || in_array($host, ['fb.watch', 'fb.com', 'fb.me'], true)) {
            return $this->facebook($link, $p, $host);
        }
        if ($this->isHost($host, 'tiktok.com')) {
            return $this->tiktok($link, $p, $host);
        }
        if ($this->isHost($host, 'youtube.com') || $host === 'youtu.be' || $this->isHost($host, 'youtube-nocookie.com')) {
            return $this->youtube($link, $p, $host);
        }
        if ($this->isHost($host, 'linkedin.com')) {
            return [false, null, 'LinkedIn tidak bisa dari link share. Buka postingan → ⋯ → "Embed this post", lalu tempel kode <iframe> yang muncul.'];
        }
        if ($this->isHost($host, 'twitter.com') || $this->isHost($host, 'x.com')) {
            return [false, null, 'X/Twitter belum didukung karena embed-nya butuh script khusus. Gunakan Instagram, Facebook, TikTok, atau YouTube.'];
        }

        return [false, null, 'Link ini belum didukung. Gunakan link/kode embed dari Instagram, Facebook, TikTok, atau YouTube.'];
    }

    private function isHost(string $host, string $domain): bool
    {
        return $host === $domain || str_ends_with($host, '.'.$domain);
    }

    private function instagram(array $p): array
    {
        if (! preg_match('~/(p|reels?|tv)/([A-Za-z0-9_-]{5,})~', $p['path'] ?? '', $m)) {
            return [false, null, 'Gunakan link postingan atau reel Instagram (contoh: instagram.com/reel/XXXX/). Link profil atau story tidak bisa ditampilkan.'];
        }
        $seg = $m[1] === 'reels' ? 'reel' : $m[1];
        $kind = $seg === 'p' ? 'post' : 'reel';
        $source = 'https://www.instagram.com/'.$seg.'/'.$m[2].'/';

        return [true, $this->result('instagram', $kind, $m[2], $source, $source.'embed/', 350, $kind === 'post' ? 640 : 700, null, null, null), null];
    }

    private function facebook(string $link, array $p, string $host): array
    {
        $path = $p['path'] ?? '';
        $isShortHost = in_array($host, ['fb.watch', 'fb.me', 'fb.com'], true);
        $isShareLink = (bool) preg_match('~^/share/(?:[rvp]/)?[A-Za-z0-9_-]+~', $path);

        // Link share Facebook (facebook.com/share/r/xxx, fb.watch/xxx, fb.me/xxx) hanyalah pengalih;
        // ikuti pengalihan agar mendapat alamat asli postingan. Bila gagal, tetap dicoba dengan link aslinya.
        $resolvedOk = false;
        if ($isShortHost || $isShareLink) {
            $resolved = $this->resolve($link);
            if ($resolved) {
                $rp = parse_url($resolved);
                $rh = strtolower(preg_replace('/^(www|m|mobile|web)\./', '', $rp['host'] ?? ''));
                if ($rp && $this->isHost($rh, 'facebook.com') && ! preg_match('~^/(login|checkpoint|unsupportedbrowser)~', $rp['path'] ?? '')) {
                    $p = $rp;
                    $path = $p['path'] ?? '';
                    $link = $resolved;
                    $host = 'facebook.com';
                    $resolvedOk = true;
                }
            }
        }

        if ($isShortHost && ! $resolvedOk) {
            // tidak bisa diikuti dari server: pakai link aslinya apa adanya
            $clean = $link;
        } else {
            if ($path === '' || $path === '/') {
                return [false, null, 'Link Facebook harus menuju postingan, video, atau reel tertentu (bukan halaman utama).'];
            }
            parse_str($p['query'] ?? '', $q);
            $keep = [];
            // pertahankan parameter yang menentukan konten (v= untuk /watch, story_fbid & id untuk permalink.php)
            foreach (['v', 'story_fbid', 'id', 'fbid', 'set'] as $k) {
                if (isset($q[$k])) {
                    $keep[$k] = $q[$k];
                }
            }
            $clean = 'https://www.facebook.com'.$path.($keep ? '?'.http_build_query($keep) : '');
        }

        $lp = strtolower($path);
        $isVideo = $host === 'fb.watch' || str_contains($lp, '/videos/') || str_contains($lp, '/reel/') || str_starts_with($lp, '/watch') || str_contains($lp, '/share/v/') || str_contains($lp, '/share/r/');
        $height = $isVideo ? 620 : 560;
        $embed = 'https://www.facebook.com/plugins/'.($isVideo ? 'video.php' : 'post.php').'?'.http_build_query([
            'href' => $clean, 'show_text' => 'true', 'width' => 350, 'height' => $height,
        ]);

        return [true, $this->result('facebook', $isVideo ? 'video' : 'post', md5($clean), $clean, $embed, 350, $height, null, null, null), null];
    }

    private function tiktok(string $link, array $p, string $host): array
    {
        $final = $link;
        if (! preg_match('~/video/(\d{8,25})~', $p['path'] ?? '', $m)) {
            $isShort = in_array($host, ['vm.tiktok.com', 'vt.tiktok.com'], true) || str_starts_with($p['path'] ?? '', '/t/');
            $resolved = $isShort ? $this->resolve($link) : null;
            if (! $resolved || ! preg_match('~/video/(\d{8,25})~', $resolved, $m)) {
                return [false, null, 'Link TikTok tidak dikenali. Pakai link video lengkap (tiktok.com/@akun/video/123…), link share pendek (vm.tiktok.com/…), atau tempel kode embed TikTok.'];
            }
            $final = $resolved;
        }
        $source = strtok($final, '?') ?: $final;
        $embed = self::tiktokPlayerUrl($m[1]);
        $real = $this->tiktokOembed($source);

        return [true, $this->result('tiktok', 'video', $m[1], $source, $embed, 360, 640,
            $real['title'] ?? null, $real['author_name'] ?? null, $real['thumbnail_url'] ?? null, $real), null];
    }

    private function youtube(string $link, array $p, string $host): array
    {
        $path = $p['path'] ?? '';
        parse_str($p['query'] ?? '', $q);
        $id = null;
        if ($host === 'youtu.be') {
            $id = trim($path, '/');
        } elseif (preg_match('~^/(?:shorts|embed|live|v)/([A-Za-z0-9_-]{6,})~', $path, $m)) {
            $id = $m[1];
        } elseif (! empty($q['v'])) {
            $id = (string) $q['v'];
        }
        if (! $id || ! preg_match('~^[A-Za-z0-9_-]{6,20}$~', $id)) {
            return [false, null, 'Link YouTube harus ke video tertentu (youtube.com/watch?v=… atau youtu.be/…).'];
        }
        $isShort = str_contains($path, '/shorts/');
        $source = $isShort ? 'https://www.youtube.com/shorts/'.$id : 'https://www.youtube.com/watch?v='.$id;
        $real = $this->youtubeOembed($source);

        return [true, $this->result('youtube', $isShort ? 'short' : 'video', $id, $source, 'https://www.youtube.com/embed/'.$id,
            $isShort ? 350 : 560, $isShort ? 620 : 315,
            $real['title'] ?? null, $real['author_name'] ?? null, $real['thumbnail_url'] ?? null, $real), null];
    }

    /* ------------------------------------------------------------------ */
    /*  Panggilan jaringan (semua opsional: gagal = tetap lanjut)          */
    /* ------------------------------------------------------------------ */

    private function tiktokOembed(string $url): ?array
    {
        return $this->publicOembed('https://www.tiktok.com/oembed', $url);
    }

    private function youtubeOembed(string $url): ?array
    {
        return $this->publicOembed('https://www.youtube.com/oembed', $url, ['format' => 'json']);
    }

    private function publicOembed(string $endpoint, string $url, array $extra = []): ?array
    {
        try {
            $res = Http::timeout(8)->acceptJson()->get($endpoint, ['url' => $url] + $extra);
            $j = $res->json();

            return ($res->successful() && is_array($j) && (! empty($j['title']) || ! empty($j['html']))) ? $j : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Ikuti pengalihan link pendek/share; kembalikan URL akhir (atau null). */
    private function resolve(string $url): ?string
    {
        try {
            $res = Http::timeout(8)
                ->withOptions(['allow_redirects' => ['max' => 6, 'track_redirects' => true]])
                ->withHeaders(['User-Agent' => self::UA, 'Accept-Language' => 'en'])
                ->get($url);
            $hist = $res->header('X-Guzzle-Redirect-History');
            if ($hist) {
                $parts = array_values(array_filter(array_map('trim', explode(',', $hist))));
                return end($parts) ?: null;
            }
        } catch (\Throwable $e) {
        }

        return null;
    }

    /* ------------------------------------------------------------------ */
    /*  Bentuk seragam                                                    */
    /* ------------------------------------------------------------------ */

    /** Bentuk seragam + dokumen oEmbed (JSON) yang disimpan. */
    private function result(string $platform, string $kind, string $id, string $source, string $embed, int $width, int $height,
        ?string $title, ?string $author, ?string $thumb, ?array $real = null): array
    {
        $iframe = self::iframeHtml($embed, $width, $height, $title ?: ucfirst($platform));

        $oembed = $real ?: [
            'version' => '1.0',
            'type' => in_array($kind, ['video', 'reel', 'short'], true) ? 'video' : 'rich',
            'provider_name' => ucfirst($platform),
            'url' => $source,
        ];
        $oembed['html'] = $iframe;          // iframe buatan sistem (aman); html asli platform tidak dipakai
        $oembed['width'] = $width;
        $oembed['height'] = $height;
        $oembed['embed_url'] = $embed;
        $oembed['source'] = $real ? $platform.'-oembed' : 'link';

        return [
            'platform' => $platform,
            'kind' => $kind,
            'external_id' => $id,
            'source_url' => $source,
            'embed_url' => $embed,
            'embed_width' => $width,
            'embed_height' => $height,
            'title' => $title ? mb_substr($title, 0, 250) : null,
            'author' => $author ? mb_substr($author, 0, 250) : null,
            'thumbnail_url' => $thumb,
            'oembed' => $oembed,
        ];
    }

    /** iframe rapi yang dibangun sistem sendiri. */
    public static function iframeHtml(string $embedUrl, int $width, int $height, string $title = ''): string
    {
        return '<iframe src="'.e($embedUrl).'" width="'.$width.'" height="'.$height.'" title="'.e($title).'"'
            .' style="border:0;overflow:hidden" scrolling="no" allowfullscreen loading="lazy"'
            .' allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>';
    }

    /** Apakah URL embed ini boleh dirender? */
    public static function isAllowedEmbed(?string $url): bool
    {
        $url = (string) $url;
        foreach (self::ALLOWED_PREFIXES as $prefix) {
            if (str_starts_with($url, $prefix)) {
                return true;
            }
        }

        return false;
    }

    private function clamp(int $v, int $min, int $max): int
    {
        return max($min, min($max, $v));
    }

    private function intOrNull($v): ?int
    {
        return is_numeric($v) ? (int) $v : null;
    }
}
