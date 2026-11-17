<?php

namespace App\Console\Commands;

use App\Services\ContentApi;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * SISI WEB: mengambil JSON dari sistem admin (superapp) lewat API, menyimpannya sebagai snapshot,
 * dan menyalin gambarnya ke storage web sendiri.
 *
 *   php artisan content:sync                       (alamat & kunci dari .env: CONTENT_SOURCE_URL / CONTENT_SOURCE_KEY)
 *   php artisan content:sync --source=http://127.0.0.1:8001 --key=abc
 *   php artisan content:sync --local               (uji cepat: bangun data dari database aplikasi ini, tanpa HTTP)
 *   php artisan content:sync --from-file=contoh.json
 *
 * Hasil:
 *   storage/app/private/content/snapshot.json   JSON terakhir yang berhasil diambil
 *   storage/app/public/remote/<path>            salinan gambar (dilayani lewat /storage/remote/<path>)
 */
class SyncContent extends Command
{
    protected $signature = 'content:sync
        {--source= : Alamat dasar sistem admin, mis. https://superapp.perusahaan.co.id}
        {--key= : API key (bila API_REQUIRE_KEY=true di sisi admin)}
        {--local : Bangun data langsung dari database aplikasi ini (tanpa HTTP)}
        {--from-file= : Baca JSON dari file}
        {--force : Abaikan ETag, selalu ambil ulang}
        {--no-images : Jangan salin gambar}
        {--insecure : Lewati verifikasi SSL (hanya untuk uji lokal)}';

    protected $description = 'Ambil JSON konten dari sistem admin dan salin gambarnya ke storage web';

    private const MAX_IMAGE_BYTES = 10 * 1024 * 1024;

    public function handle(): int
    {
        $payload = $this->obtain();
        if ($payload === false) {
            return self::FAILURE;
        }
        if ($payload === null) {
            return self::SUCCESS; // 304: belum ada perubahan
        }
        if (! isset($payload['resources']) || ! is_array($payload['resources'])) {
            $this->error('Respons bukan JSON konten yang dikenal (tidak ada "resources").');

            return self::FAILURE;
        }

        Storage::disk('local')->put(
            'content/snapshot.json',
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        );

        $rows = [];
        foreach ($payload['resources'] as $name => $data) {
            $rows[] = [$name, is_array($data) ? count($data) : 0];
        }
        $this->info('Snapshot tersimpan (revisi '.($payload['revision'] ?? '-').').');
        $this->table(['Resource', 'Jumlah'], $rows);

        if (! $this->option('no-images')) {
            $stats = $this->syncImages($payload);
            $this->info(sprintf(
                'Gambar: %d baru, %d diperbarui, %d dilewati (sudah sama), %d gagal.',
                $stats['new'], $stats['updated'], $stats['skipped'], $stats['failed']
            ));

            return $stats['failed'] > 0 ? self::FAILURE : self::SUCCESS;
        }

        return self::SUCCESS;
    }

    /** @return array|null|false  array = data, null = tidak berubah, false = gagal */
    private function obtain(): array|null|false
    {
        if ($file = $this->option('from-file')) {
            if (! is_file($file)) {
                $this->error("File tidak ditemukan: $file");

                return false;
            }
            $json = json_decode((string) file_get_contents($file), true);

            return is_array($json) ? $json : $this->reportFailure('JSON pada file tidak valid.');
        }

        if ($this->option('local')) {
            $this->line('Mode lokal: data dibangun dari database aplikasi ini.');

            return ContentApi::build();
        }

        $source = rtrim((string) ($this->option('source') ?: config('content_api.source_url')), '/');
        if ($source === '') {
            $this->error('Alamat sumber belum diatur. Isi CONTENT_SOURCE_URL di .env atau pakai --source=...');

            return false;
        }
        $key = (string) ($this->option('key') ?: config('content_api.source_key'));

        $http = $this->http($key);
        $etag = $this->option('force') ? null : Storage::disk('local')->get('content/etag.txt');
        if ($etag) {
            $http = $http->withHeaders(['If-None-Match' => $etag]);
        }

        $this->line("Mengambil $source/api/v1/all ...");
        try {
            $res = $http->get($source.'/api/v1/all');
        } catch (\Throwable $e) {
            return $this->reportFailure('Tidak bisa terhubung ke sumber: '.$e->getMessage());
        }

        if ($res->status() === 304) {
            $this->info('Tidak ada perubahan sejak sinkronisasi terakhir.');

            return null;
        }
        if (in_array($res->status(), [401, 403, 503], true)) {
            return $this->reportFailure('Ditolak oleh sumber ('.$res->status().'): '.($res->json('message') ?: 'cek API key / IP.'));
        }
        if (! $res->successful()) {
            return $this->reportFailure('Sumber menjawab status '.$res->status().'.');
        }

        $json = $res->json();
        if (! is_array($json)) {
            return $this->reportFailure('Respons sumber bukan JSON.');
        }
        if ($newTag = $res->header('ETag')) {
            Storage::disk('local')->put('content/etag.txt', $newTag);
        }

        return $json;
    }

    private function reportFailure(string $message): false
    {
        $this->error($message);

        return false;
    }

    private function http(?string $key = null): PendingRequest
    {
        $http = Http::timeout(30)->acceptJson();
        if ($key) {
            $http = $http->withHeaders(['X-API-Key' => $key]);
        }
        if ($this->option('insecure')) {
            $http = $http->withoutVerifying();
        }

        return $http;
    }

    /** Salin semua objek gambar {path,url,size,modified} ke storage/app/public/remote/. */
    private function syncImages(array $payload): array
    {
        $found = [];
        $this->collectImages($payload['resources'], $found);

        $pub = Storage::disk('public');
        $local = Storage::disk('local');
        $manifest = json_decode((string) $local->get('content/images.json'), true);
        $manifest = is_array($manifest) ? $manifest : [];
        $stats = ['new' => 0, 'updated' => 0, 'skipped' => 0, 'failed' => 0];
        $http = $this->http(); // gambar publik: API key tidak dikirim

        foreach ($found as $path => $img) {
            if (! preg_match('~^[A-Za-z0-9._\-/]+$~', $path) || str_contains($path, '..') || str_starts_with($path, '/')) {
                $this->warn("Dilewati (path tidak aman): $path");
                $stats['failed']++;

                continue;
            }

            $target = 'remote/'.$path;
            $signature = ($img['size'] ?? 0).'|'.($img['modified'] ?? '');
            $existed = $pub->exists($target);

            if ($existed && ($manifest[$path] ?? null) === $signature) {
                $stats['skipped']++;

                continue;
            }

            try {
                if ($this->option('local') && $pub->exists($path)) {
                    $bytes = $pub->get($path);          // sumber = aplikasi ini sendiri: salin langsung dari disk
                } else {
                    $res = $http->get((string) $img['url']);
                    if (! $res->successful()) {
                        throw new \RuntimeException('HTTP '.$res->status());
                    }
                    if (! str_starts_with(strtolower((string) $res->header('Content-Type')), 'image/')) {
                        throw new \RuntimeException('bukan file gambar ('.$res->header('Content-Type').')');
                    }
                    $bytes = $res->body();
                }

                if ($bytes === null || $bytes === '' || strlen($bytes) > self::MAX_IMAGE_BYTES) {
                    throw new \RuntimeException('file kosong atau lebih dari 10 MB');
                }

                $pub->put($target, $bytes);
                $manifest[$path] = $signature;
                $existed ? $stats['updated']++ : $stats['new']++;
            } catch (\Throwable $e) {
                $stats['failed']++;
                $this->warn("Gagal menyalin $path: ".$e->getMessage());
            }
        }

        $local->put('content/images.json', json_encode($manifest, JSON_UNESCAPED_SLASHES));

        return $stats;
    }

    private function collectImages(array $node, array &$found): void
    {
        if (isset($node['path'], $node['url'], $node['size']) && is_string($node['path']) && is_string($node['url'])) {
            $found[$node['path']] = $node;

            return;
        }

        foreach ($node as $value) {
            if (is_array($value)) {
                $this->collectImages($value, $found);
            }
        }
    }
}
