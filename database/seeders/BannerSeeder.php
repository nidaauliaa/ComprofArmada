<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

/** Banner awal sesuai prototype (company-profile/index.html). Hanya mengisi bila tabel banner masih kosong. */
class BannerSeeder extends Seeder
{
    public function run(): void
    {
        if (Banner::query()->exists()) {
            return;
        }

        $rows = [
            [
                'image_path' => 'images/skylander-r25.jpeg', 'sort_order' => 1,
                'tag' => 'New Armada Karoseri', 'tag_en' => 'New Armada Coachbuilder', 'tag_zh' => 'New Armada 车身制造',
                'title' => 'Karoseri Bus dari Magelang', 'title_en' => 'Bus Bodies from Magelang', 'title_zh' => '来自马格朗的客车车身',
                'headline' => 'untuk Indonesia', 'headline_en' => 'for Indonesia', 'headline_zh' => '服务印度尼西亚',
                'description' => 'Together we are strong. Menghadirkan kenyamanan, keamanan, dan keandalan berkelas internasional.',
                'description_en' => 'Together we are strong. Delivering international-class comfort, safety, and reliability.',
                'description_zh' => '团结就是力量。为您带来国际水准的舒适、安全与可靠。',
                'button_text' => 'Lihat Kategori Bus ↓', 'button_text_en' => 'View Bus Categories ↓', 'button_text_zh' => '查看客车类别 ↓',
                'button_link' => '#produk',
            ],
            [
                'image_path' => null, 'sort_order' => 2,
                'tag' => 'Inovasi Manufaktur', 'tag_en' => 'Manufacturing Innovation', 'tag_zh' => '制造创新',
                'title' => 'Dirakit Teliti Dengan', 'title_en' => 'Carefully Built With', 'title_zh' => '精心组装，',
                'headline' => 'Standar Kualitas Presisi', 'headline_en' => 'Precision Quality Standards', 'headline_zh' => '精准的质量标准',
                'description' => 'Fasilitas manufaktur modern yang siap melayani kebutuhan armada pariwisata & antarkota di seluruh Indonesia.',
                'description_en' => 'Modern manufacturing facilities ready to serve tourism and intercity fleet needs across Indonesia.',
                'description_zh' => '现代化制造设施，满足全印尼旅游及城际车队的需求。',
                'button_text' => 'Jelajahi Lokasi Plant', 'button_text_en' => 'Explore Plant Locations', 'button_text_zh' => '浏览工厂位置',
                'button_link' => '#plant',
            ],
        ];

        foreach ($rows as $row) {
            Banner::query()->create($row + ['is_active' => true]);
        }
    }
}
