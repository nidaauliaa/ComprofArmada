<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryToastTest extends TestCase
{
    use RefreshDatabase;

    public function test_saving_category_renders_category_toast_and_updates_category(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Bus Uji',
            'slug' => 'bus-uji',
            'color_bg' => '#12274d',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->put(route('admin.products.categories.update', $category), [
                'name' => 'Bus Uji Baru',
                'short_text' => 'UJI',
                'color_bg' => '#12274d',
            ]);

        $response->assertOk()
            ->assertSee('id="adminFlashToast"', false)
            ->assertSee('data-tone="category"', false)
            ->assertSee('data-message="Kategori berhasil diperbarui."', false);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Bus Uji Baru',
        ]);
    }
}
