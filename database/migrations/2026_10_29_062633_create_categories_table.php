<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Big Bus', 'short_text' => 'BIG', 'color_bg' => '#12274d', 'sort_order' => 1],
            ['name' => 'Medium Bus', 'short_text' => 'MED', 'color_bg' => '#0c4a7a', 'sort_order' => 2],
            ['name' => 'Intercity', 'short_text' => 'INTER', 'color_bg' => '#0a7bb8', 'sort_order' => 3],
            ['name' => 'Microbus', 'short_text' => 'MICRO', 'color_bg' => '#4a7fa3', 'sort_order' => 4],
            ['name' => 'Box & Heavy Duty', 'short_text' => 'HEAVY', 'color_bg' => '#b0574a', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'short_text' => $cat['short_text'],
                    'color_bg' => $cat['color_bg'],
                    'is_active' => true,
                    'sort_order' => $cat['sort_order'],
                ]
            );
        }
    }
}
