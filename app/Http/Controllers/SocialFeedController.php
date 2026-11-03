<?php

namespace App\Http\Controllers;

use App\Models\SocialPost;

class SocialFeedController extends Controller
{
    /** JSON publik: daftar konten sosial media yang tampil di web (hasil konversi link / kode embed -> oEmbed). */
    public function index()
    {
        $rows = SocialPost::query()->visible()->get();

        return response()->json([
            'count' => $rows->count(),
            'items' => $rows->map(fn ($r) => [
                'id' => $r->id,
                'platform' => $r->platform,
                'kind' => $r->kind,
                'title' => $r->title,
                'author' => $r->author,
                'source_url' => $r->source_url,
                'embed_url' => $r->safeEmbedUrl(),
                'width' => $r->embedWidth(),
                'height' => $r->embedHeight(),
                'thumbnail_url' => $r->thumbnail_url,
                'oembed' => $r->oembed,
            ])->values(),
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Access-Control-Allow-Origin', '*');
    }
}
