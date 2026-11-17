<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pengaman API konten. Mode ditentukan di .env (lihat config/content_api.php):
 *   API_REQUIRE_KEY=false  -> terbuka (read-only, hanya konten publik)
 *   API_REQUIRE_KEY=true   -> wajib API key (header X-API-Key atau Authorization: Bearer)
 *   API_ALLOWED_IPS=...    -> (opsional) hanya IP tertentu
 */
class ContentApiAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = config('content_api.allowed_ips', []);
        if ($allowedIps && ! in_array($request->ip(), $allowedIps, true)) {
            return response()->json(['message' => 'Akses dari alamat IP ini tidak diizinkan.'], 403);
        }

        if (config('content_api.require_key')) {
            $keys = config('content_api.keys', []);
            if (! $keys) {
                return response()->json(['message' => 'API key belum diatur di server (API_KEYS).'], 503);
            }

            $given = (string) ($request->header('X-API-Key') ?: $request->bearerToken());
            $ok = false;
            foreach ($keys as $key) {
                if ($given !== '' && hash_equals($key, $given)) {
                    $ok = true;
                    break;
                }
            }

            if (! $ok) {
                return response()->json(['message' => 'API key tidak valid atau tidak dikirim.'], 401, ['WWW-Authenticate' => 'Bearer']);
            }
        }

        return $next($request);
    }
}
