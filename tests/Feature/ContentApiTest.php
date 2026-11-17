<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\News;
use App\Models\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('local');
    }

    private function banner(array $over = []): Banner
    {
        return Banner::query()->create($over + [
            'is_active' => true, 'title' => 'Judul', 'title_en' => 'Title', 'title_zh' => '标题',
            'button_link' => '#produk', 'sort_order' => 1,
        ]);
    }

    public function test_terbuka_tanpa_key_bisa_dipanggil_curl(): void
    {
        config(['content_api.require_key' => false]);

        $this->getJson('/api/v1')->assertOk()->assertJsonPath('name', 'New Armada Content API');
        $this->getJson('/api/v1/manifest')->assertOk()->assertJsonStructure(['revision', 'counts', 'revisions']);
    }

    public function test_mode_api_key(): void
    {
        config(['content_api.require_key' => true, 'content_api.keys' => ['kunci-web']]);

        $this->getJson('/api/v1/all')->assertStatus(401);
        $this->getJson('/api/v1/all', ['X-API-Key' => 'salah'])->assertStatus(401);
        $this->getJson('/api/v1/all', ['X-API-Key' => 'kunci-web'])->assertOk();
        $this->getJson('/api/v1/all', ['Authorization' => 'Bearer kunci-web'])->assertOk();
    }

    public function test_mode_key_tanpa_kunci_terdaftar_ditolak_aman(): void
    {
        config(['content_api.require_key' => true, 'content_api.keys' => []]);

        $this->getJson('/api/v1/all', ['X-API-Key' => 'apa-saja'])->assertStatus(503);
    }

    public function test_batas_ip(): void
    {
        config(['content_api.allowed_ips' => ['10.9.9.9']]);

        $this->getJson('/api/v1/manifest')->assertStatus(403);
    }

    public function test_hanya_konten_publik_yang_ikut(): void
    {
        $this->banner(['title' => 'Aktif']);
        $this->banner(['title' => 'Draf', 'is_active' => false]);

        News::query()->create(['slug' => 'terbit', 'title' => ['id' => 'Terbit', 'en' => 'Published'], 'published_at' => now()->subDay(), 'is_active' => true]);
        News::query()->create(['slug' => 'nanti', 'title' => ['id' => 'Nanti'], 'published_at' => now()->addWeek(), 'is_active' => true]);
        News::query()->create(['slug' => 'draf', 'title' => ['id' => 'Draf'], 'published_at' => now()->subDay(), 'is_active' => false]);

        $off = Category::query()->create(['name' => 'Off', 'slug' => 'off', 'is_active' => false]);
        ProductModel::query()->create(['category_id' => $off->id, 'name' => 'X', 'slug' => 'x', 'is_active' => true]);

        $this->getJson('/api/v1/banners')->assertOk()->assertJsonPath('count', 1)->assertJsonPath('data.0.title.id', 'Aktif');
        $this->getJson('/api/v1/news')->assertOk()->assertJsonPath('count', 1)->assertJsonPath('data.0.slug', 'terbit');
        $this->getJson('/api/v1/products')->assertOk()->assertJsonPath('count', 0);
    }

    public function test_teks_tiga_bahasa_dan_objek_gambar(): void
    {
        Storage::disk('public')->put('banners/asli.jpg', 'abc');
        $this->banner(['image_path' => 'banners/asli.jpg']);

        $r = $this->getJson('/api/v1/banners')->assertOk();
        $r->assertJsonPath('data.0.title', ['id' => 'Judul', 'en' => 'Title', 'zh' => '标题']);
        $r->assertJsonPath('data.0.image.path', 'banners/asli.jpg');
        $r->assertJsonPath('data.0.image.size', 3);
        $r->assertJsonPath('data.0.image.fallback', false);
        $this->assertStringEndsWith('/storage/banners/asli.jpg', $r->json('data.0.image.url'));
    }

    public function test_file_unggahan_hilang_memakai_gambar_bawaan(): void
    {
        Storage::disk('public')->put('images/skylander-r25.jpeg', 'bawaan');
        $this->banner(['image_path' => 'banners/hilang.jpg']);

        $this->getJson('/api/v1/banners')
            ->assertJsonPath('data.0.image.path', 'images/skylander-r25.jpeg')
            ->assertJsonPath('data.0.image.fallback', true);
    }

    public function test_etag_menghasilkan_304_bila_tidak_berubah(): void
    {
        $this->banner();

        $first = $this->getJson('/api/v1/all')->assertOk();
        $etag = $first->headers->get('ETag');
        $this->assertNotEmpty($etag);

        $this->getJson('/api/v1/all', ['If-None-Match' => $etag])->assertStatus(304);
    }

    public function test_cache_otomatis_bersih_saat_konten_diubah(): void
    {
        $b = $this->banner(['title' => 'Lama']);
        $this->getJson('/api/v1/banners')->assertJsonPath('data.0.title.id', 'Lama');

        $b->update(['title' => 'Baru']);

        $this->getJson('/api/v1/banners')->assertJsonPath('data.0.title.id', 'Baru');
    }

    public function test_resource_tidak_dikenal(): void
    {
        $this->getJson('/api/v1/rahasia')->assertStatus(404)->assertJsonStructure(['message', 'available']);
    }

    public function test_sync_mengambil_json_dan_menyalin_gambar(): void
    {
        $payload = [
            'version' => '1', 'revision' => 'r1',
            'resources' => [
                'news' => [[
                    'slug' => 'a', 'title' => ['id' => 'A', 'en' => '', 'zh' => ''],
                    'thumbnail' => ['path' => 'news/a.webp', 'url' => 'http://sumber.test/storage/news/a.webp', 'size' => 3, 'modified' => '2026-11-17T00:00:00+00:00', 'fallback' => false],
                    'blocks' => [],
                ]],
                'banners' => [],
            ],
        ];
        Http::fake([
            'sumber.test/api/v1/all' => Http::response($payload, 200, ['ETag' => '"abc"']),
            'sumber.test/storage/*' => Http::response('IMG', 200, ['Content-Type' => 'image/webp']),
        ]);

        $this->artisan('content:sync', ['--source' => 'http://sumber.test', '--key' => 'k'])->assertExitCode(0);

        Storage::disk('local')->assertExists('content/snapshot.json');
        Storage::disk('public')->assertExists('remote/news/a.webp');
        $this->assertSame('IMG', Storage::disk('public')->get('remote/news/a.webp'));

        Http::assertSent(fn ($req) => $req->hasHeader('X-API-Key', 'k') || str_contains($req->url(), '/storage/'));
    }

    public function test_sync_ditolak_sumber_bila_key_salah(): void
    {
        Http::fake(['sumber.test/*' => Http::response(['message' => 'API key tidak valid'], 401)]);

        $this->artisan('content:sync', ['--source' => 'http://sumber.test'])->assertExitCode(1);
        Storage::disk('local')->assertMissing('content/snapshot.json');
    }

    public function test_sync_mode_lokal_menyalin_gambar_dari_disk(): void
    {
        Storage::disk('public')->put('banners/asli.jpg', 'abc');
        $this->banner(['image_path' => 'banners/asli.jpg']);

        $this->artisan('content:sync', ['--local' => true])->assertExitCode(0);

        Storage::disk('public')->assertExists('remote/banners/asli.jpg');
        Storage::disk('local')->assertExists('content/snapshot.json');
    }
}
