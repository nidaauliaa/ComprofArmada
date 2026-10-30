<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** Data contoh dari prototype. Jalankan: php artisan db:seed --class=NewsSeeder */
class NewsSeeder extends Seeder
{
    public function run(): void
    {
        // [judul, kategori, tanggal, thumbnail (di public/images), isi singkat, warna]
        $items = [
        ['Skylander R25, Siap Menempuh Jarak Jauh', 'News', '2026-10-24', 'images/skylander-r25.jpeg', 'Teks berita contoh. Skylander R25 hadir dengan kabin yang lebih luas, kursi nyaman, dan fitur keselamatan terbaru untuk perjalanan antarkota maupun pariwisata. Ganti dengan isi berita asli.', '#12274d'],
        ['Big Bus Double Decker untuk Perjalanan Wisata', 'News', '2026-10-18', 'images/BIGBUS-DOUBLEDECKER.png', 'Teks berita contoh tentang kapasitas, desain, dan keunggulan unit double decker untuk wisata keliling kota.', '#12274d'],
        ['Medium Bus Lincah dan Nyaman untuk Rute Pariwisata', 'News', '2026-10-12', 'images/MEDIUM-BUS.png', 'Teks berita contoh tentang spesifikasi medium bus dan layanan purna jual.', '#0c4a7a'],
        ['Intercity Citouro, Pilihan Armada Antarkota', 'News', '2026-10-05', 'images/INTERCITY-2.png', 'Teks berita contoh tentang pengiriman unit dan kerja sama dengan operator bus.', '#0a7bb8'],
        ['Box dan Heavy Duty yang Tangguh untuk Distribusi', 'News', '2026-09-28', 'images/BOX.png', 'Teks berita contoh tentang produk niaga dan kebutuhan logistik.', '#b0574a'],
        ['Microbus Praktis untuk Layanan Antar-Jemput', 'News', '2026-09-21', 'images/MICROBUS-2.png', 'Teks berita contoh tentang microbus untuk kebutuhan antar-jemput dan perjalanan keluarga.', '#4a7fa3'],
        ['Kunjungan Pelanggan ke Plant Magelang', 'Event', '2026-09-14', 'images/BIGBUS-DOUBLEDECKER.png', 'Teks berita contoh tentang kunjungan pelanggan melihat proses produksi karoseri.', '#0c4a7a'],
        ['Pengiriman Perdana Unit Terbaru ke Operator', 'News', '2026-09-07', 'images/INTERCITY-2.png', 'Teks berita contoh tentang pengiriman perdana unit terbaru kepada operator bus.', '#12274d'],
        ['Standar Kualitas dan Keselamatan di Setiap Unit', 'Event', '2026-08-30', 'images/MEDIUM-BUS.png', 'Teks berita contoh tentang proses kontrol kualitas dan standar keselamatan produksi.', '#b0574a'],
        ];

        foreach ($items as [$title, $category, $date, $thumb, $body]) {
            News::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title'          => ['id' => $title, 'en' => '', 'zh' => ''],
                    'category'       => $category,
                    'published_at'   => $date,
                    'thumbnail_path' => $thumb,
                    'blocks'         => [['type' => 'p', 'text' => ['id' => $body, 'en' => '', 'zh' => '']]],
                    'is_active'      => true,
                ]
            );
        }
    }
}
