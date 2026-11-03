<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ProductModel;
use App\Models\SocialPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SocialAndLanguageTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_pengalih_bahasa(): void
    {
        $this->get('/')->assertOk()->assertSee('lang="id"', false);
        $this->get('/bahasa/en')->assertRedirect()->assertSessionHas('locale', 'en');
        $this->get('/?lang=en')->assertOk()->assertSee('lang="en"', false)->assertSee('Contact Us');
        $this->get('/?lang=zh')->assertOk()->assertSee('联系我们');
        $this->get('/bahasa/xx')->assertNotFound();
    }

    public function test_kategori_dan_produk_mengikuti_bahasa(): void
    {
        $cat = Category::query()->create(['name' => 'Medium Bus', 'name_zh' => '中型客车', 'slug' => 'medium-bus']);
        ProductModel::query()->create(['category_id' => $cat->id, 'name' => 'Tourista', 'slug' => 'tourista', 'subtitle' => 'Pariwisata', 'subtitle_en' => 'Tourism']);

        $this->get('/produk?kategori=medium-bus&lang=zh')->assertSee('中型客车');
        $this->get('/produk?kategori=medium-bus&lang=en')->assertSee('Tourism');
        $this->get('/produk?kategori=medium-bus&lang=id')->assertSee('Pariwisata');
    }

    public function test_link_instagram_jadi_embed_dan_tampil_di_home(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/social', ['link' => 'https://www.instagram.com/reel/C1a2B3c4D5e/?igsh=abc'])
            ->assertRedirect('/admin/social');

        $this->assertDatabaseHas('social_posts', [
            'platform' => 'instagram', 'kind' => 'reel',
            'embed_url' => 'https://www.instagram.com/reel/C1a2B3c4D5e/embed/',
        ]);
        $this->getJson('/api/social')->assertOk()->assertJsonPath('items.0.platform', 'instagram');
        $this->get('/')->assertSee('instagram.com/reel/C1a2B3c4D5e/embed/', false);
    }

    public function test_kode_embed_facebook_langsung_diterima(): void
    {
        $iframe = '<iframe src="https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2Freel%2F1499956438719166%2F&show_text=true&width=267&t=0" width="267" height="591" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>';

        $this->actingAs($this->admin())->post('/admin/social', ['link' => $iframe])->assertRedirect('/admin/social');

        $p = SocialPost::query()->firstOrFail();
        $this->assertSame('facebook', $p->platform);
        $this->assertSame(267, $p->embed_width);
        $this->assertSame(591, $p->embed_height);
        $this->assertStringStartsWith('https://www.facebook.com/plugins/video.php', $p->embed_url);
        $this->get('/')->assertSee('facebook.com/plugins/video.php', false);
    }

    public function test_iframe_ke_situs_lain_ditolak(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/social', ['link' => '<iframe src="https://evil.example.com/x"></iframe>'])
            ->assertSessionHas('error');
        $this->assertDatabaseCount('social_posts', 0);
    }

    public function test_facebook_dan_tiktok_dari_link_share(): void
    {
        Http::fake([
            'www.tiktok.com/oembed*' => Http::response(['title' => 'Video Armada', 'author_name' => 'newarmada', 'thumbnail_url' => 'https://example.com/t.jpg', 'html' => '<blockquote></blockquote>']),
            '*' => Http::response('', 200),
        ]);

        $this->actingAs($this->admin());
        $this->post('/admin/social', ['link' => 'https://www.facebook.com/newarmada/posts/12345?fbclid=xx'])->assertRedirect('/admin/social');
        $this->post('/admin/social', ['link' => 'https://www.tiktok.com/@newarmada/video/7123456789012345678'])->assertRedirect('/admin/social');

        $this->assertDatabaseHas('social_posts', ['platform' => 'tiktok', 'title' => 'Video Armada', 'embed_url' => 'https://www.tiktok.com/embed/v2/7123456789012345678']);
        $fb = SocialPost::query()->where('platform', 'facebook')->firstOrFail();
        $this->assertStringNotContainsString('fbclid', $fb->embed_url);
    }

    public function test_json_oembed_bisa_ditempel_dan_diedit_manual(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/social', ['link' => '{"title":"Reel Pabrik","html":"<iframe src=\"https://www.instagram.com/reel/AAAAA11111/embed/\" width=\"400\" height=\"650\"></iframe>"}'])
            ->assertRedirect('/admin/social');
        $p = SocialPost::query()->firstOrFail();
        $this->assertSame('Reel Pabrik', $p->title);
        $this->assertSame(400, $p->embed_width);

        $json = $p->editableJson();
        $json['title'] = 'Judul diubah manual';
        $json['height'] = 800;
        $this->put("/admin/social/{$p->id}", ['json' => json_encode($json)])->assertRedirect('/admin/social');
        $p->refresh();
        $this->assertSame('Judul diubah manual', $p->title);
        $this->assertSame(800, $p->embed_height);

        $json['embed_url'] = 'https://evil.example.com/x';
        $this->put("/admin/social/{$p->id}", ['json' => json_encode($json)])->assertSessionHas('error');
        $this->put("/admin/social/{$p->id}", ['json' => '{rusak'])->assertSessionHas('error');
        $this->assertSame('Judul diubah manual', $p->fresh()->title);
    }

    public function test_duplikat_ditolak_dan_butuh_login(): void
    {
        $this->get('/admin/social')->assertRedirect('/login');
        $this->actingAs($this->admin());
        $this->post('/admin/social', ['link' => 'https://www.instagram.com/p/AAAAA11111/']);
        $this->post('/admin/social', ['link' => 'https://www.instagram.com/p/AAAAA11111/'])->assertSessionHas('error');
        $this->post('/admin/social', ['link' => 'https://www.instagram.com/newarmada.official/'])->assertSessionHas('error');
        $this->assertDatabaseCount('social_posts', 1);
    }

    public function test_pencarian_produk(): void
    {
        $cat = Category::query()->create(['name' => 'Big Bus', 'slug' => 'big-bus']);
        ProductModel::query()->create(['category_id' => $cat->id, 'name' => 'Skylander R25', 'slug' => 'skylander-r25', 'subtitle' => 'Big Bus']);

        $this->get('/cari?q=Skylander')->assertOk()->assertSee('Skylander R25');
        $this->getJson('/cari/saran?q=Skyl')->assertOk()->assertJsonPath('0.title', 'Skylander R25');
    }
}
