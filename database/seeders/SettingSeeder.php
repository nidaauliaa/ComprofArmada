<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/** Isi awal Pengaturan Situs (aman dijalankan berulang, tidak menimpa isian yang sudah diubah marketing). */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name' => 'New Armada',
            'address' => 'Jl. Mayjen Bambang Soegeng No. 7, Mertoyudan, Magelang, Jawa Tengah',
            'phone' => '(0293) 363591, 364371',
            'email_marketing' => 'marketing@newarmada.co.id',
            'working_hours' => 'Senin–Jumat, 08.00–16.00 WIB',
            'social_instagram' => 'https://instagram.com/newarmada.official',
            'youtube_video_id' => 'nYarnSGbWqE',
            'ig_posts' => '2.641',
            'ig_followers' => '109 rb',
            'ig_following' => '525',
            'meta_description' => json_encode([
                'id' => 'New Armada – karoseri bus dari Magelang untuk Indonesia: Big Bus, Medium Bus, Intercity, Microbus, Box & Heavy Duty.',
                'en' => 'New Armada – bus coachbuilder from Magelang for Indonesia: Big Bus, Medium Bus, Intercity, Microbus, Box & Heavy Duty.',
                'zh' => '',
            ], JSON_UNESCAPED_UNICODE),
        ];

        foreach ($defaults as $key => $value) {
            if (Setting::query()->where('key', $key)->doesntExist()) {
                Setting::put($key, $value);
            }
        }
    }
}
