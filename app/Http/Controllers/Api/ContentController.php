<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ContentApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** API konten publik (GET saja). Lihat docs/API-MANAGEMENT.md. */
class ContentController extends Controller
{
    private const FLAGS = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

    /** Daftar endpoint. */
    public function index(): JsonResponse
    {
        $base = url('/api/v1');

        return response()->json([
            'name' => 'New Armada Content API',
            'version' => ContentApi::VERSION,
            'endpoints' => [
                'manifest' => $base.'/manifest',
                'all' => $base.'/all',
                'settings' => $base.'/settings',
                'banners' => $base.'/banners',
                'categories' => $base.'/categories',
                'products' => $base.'/products',
                'news' => $base.'/news',
                'plants' => $base.'/plants',
                'social' => $base.'/social',
                'sales_contacts' => $base.'/sales_contacts',
            ],
            'auth' => config('content_api.require_key') ? 'API key (X-API-Key / Bearer)' : 'terbuka (read-only)',
        ], 200, [], self::FLAGS);
    }

    /** Ringkasan kecil: jumlah dan "revisi" tiap resource, untuk mengecek ada perubahan atau tidak. */
    public function manifest(Request $request): JsonResponse
    {
        $p = ContentApi::payload();

        return $this->respond($request, [
            'version' => $p['version'],
            'base_url' => $p['base_url'],
            'generated_at' => $p['generated_at'],
            'revision' => $p['revision'],
            'revisions' => $p['revisions'],
            'counts' => $p['counts'],
        ], $p['revision'].'-manifest');
    }

    /** Semua resource dalam satu JSON (dipakai perintah content:sync). */
    public function all(Request $request): JsonResponse
    {
        $p = ContentApi::payload();

        return $this->respond($request, $p, $p['revision']);
    }

    /** Satu resource, mis. /api/v1/news */
    public function show(Request $request, string $resource): JsonResponse
    {
        $p = ContentApi::payload();

        if (! array_key_exists($resource, $p['resources'])) {
            return response()->json([
                'message' => 'Resource tidak ditemukan.',
                'available' => array_keys($p['resources']),
            ], 404);
        }

        return $this->respond($request, [
            'version' => $p['version'],
            'base_url' => $p['base_url'],
            'generated_at' => $p['generated_at'],
            'revision' => $p['revisions'][$resource],
            'count' => $p['counts'][$resource],
            'data' => $p['resources'][$resource],
        ], $p['revisions'][$resource]);
    }

    /** JSON + ETag: pemanggil yang mengirim If-None-Match menerima 304 bila isi belum berubah. */
    private function respond(Request $request, array $data, string $etagSeed): JsonResponse
    {
        $response = response()->json($data, 200, [], self::FLAGS);
        $response->setEtag(md5($etagSeed));
        $response->headers->set('Cache-Control', 'no-cache, private');
        $response->isNotModified($request);

        return $response;
    }
}
