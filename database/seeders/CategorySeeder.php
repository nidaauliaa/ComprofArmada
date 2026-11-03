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
            ['name' => 'Big Bus', 'name_en' => 'Big Bus', 'name_zh' => '大型客车', 'image' => 'images/BIGBUS-DOUBLEDECKER.png', 'short_text' => 'BIG', 'color_bg' => '#12274d', 'sort_order' => 1],
            ['name' => 'Medium Bus', 'name_en' => 'Medium Bus', 'name_zh' => '中型客车', 'image' => 'images/MEDIUM-BUS.png', 'short_text' => 'MED', 'color_bg' => '#0c4a7a', 'sort_order' => 2],
            ['name' => 'Intercity', 'name_en' => 'Intercity', 'name_zh' => '城际客车', 'image' => 'images/INTERCITY-2.png', 'short_text' => 'INTER', 'color_bg' => '#0a7bb8', 'sort_order' => 3],
            ['name' => 'Microbus', 'name_en' => 'Microbus', 'name_zh' => '微型客车', 'image' => 'images/MICROBUS-2.png', 'short_text' => 'MICRO', 'color_bg' => '#4a7fa3', 'sort_order' => 4],
            ['name' => 'Box & Heavy Duty', 'name_en' => 'Box & Heavy Duty', 'name_zh' => '厢式及重型车', 'image' => 'images/BOX.png', 'short_text' => 'HEAVY', 'color_bg' => '#b0574a', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'name_en' => $cat['name_en'],
                    'name_zh' => $cat['name_zh'],
                    // gambar bawaan ada di storage/app/public/images (jalankan: php artisan storage:link)
                    'image_path' => $cat['image'],
                    'short_text' => $cat['short_text'],
                    'color_bg' => $cat['color_bg'],
                    'is_active' => true,
                    'sort_order' => $cat['sort_order'],
                ]
            );
        }
    }
}
