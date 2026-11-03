<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProductModel;
use Illuminate\Database\Seeder;

/**
 * Model produk contoh dari prototype (company-profile/data.js). Aman dijalankan berulang (updateOrCreate per slug).
 * Jalankan SETELAH CategorySeeder:  php artisan db:seed --class=CatalogSeeder
 * Gambar model memakai poster kategori; ganti foto asli lewat menu admin Produk.
 */
class ProductModelSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['big-bus', 'Skylander R25 DD', 'skylander-r25-dd', 'Double decker', '13,5 m', 'Skylander R25 DD – Double decker. Kapasitas: 56 kursi. Sasis: Sasis 2 gandar. Panjang: 13,5 m.', 1],
            ['big-bus', 'Skylander R25 SHD', 'skylander-r25-shd', 'Super high deck', '13,0 m', 'Skylander R25 SHD – Super high deck. Kapasitas: 40 kursi. Sasis: Sasis 2 gandar. Panjang: 13,0 m.', 2],
            ['big-bus', 'Skylander R22', 'skylander-r22', 'High deck', '12,5 m', 'Skylander R22 – High deck. Kapasitas: 48 kursi. Sasis: Sasis 2 gandar. Panjang: 12,5 m.', 3],
            ['big-bus', 'Skylander R21 Executive', 'skylander-r21-executive', 'Executive', '12,0 m', 'Skylander R21 Executive – Executive. Kapasitas: 32 kursi. Sasis: Sasis 2 gandar. Panjang: 12,0 m.', 4],
            ['medium-bus', 'Tourista 25', 'tourista-25', 'Pariwisata', '8,0 m', 'Tourista 25 – Pariwisata. Kapasitas: 25 kursi. Sasis: Sasis medium. Panjang: 8,0 m.', 1],
            ['medium-bus', 'Tourista 29', 'tourista-29', 'Pariwisata', '8,5 m', 'Tourista 29 – Pariwisata. Kapasitas: 29 kursi. Sasis: Sasis medium. Panjang: 8,5 m.', 2],
            ['medium-bus', 'Tourista 33', 'tourista-33', 'Pariwisata', '9,0 m', 'Tourista 33 – Pariwisata. Kapasitas: 33 kursi. Sasis: Sasis medium. Panjang: 9,0 m.', 3],
            ['medium-bus', 'Tourista 35 Executive', 'tourista-35-executive', 'Executive', '9,5 m', 'Tourista 35 Executive – Executive. Kapasitas: 35 kursi. Sasis: Sasis medium. Panjang: 9,5 m.', 4],
            ['intercity', 'Citouro City Tour', 'citouro-city-tour', 'City tour', '8,5 m', 'Citouro City Tour – City tour. Kapasitas: 30 kursi. Sasis: Sasis medium. Panjang: 8,5 m.', 1],
            ['intercity', 'Citouro Transit', 'citouro-transit', 'Angkutan kota', '9,0 m', 'Citouro Transit – Angkutan kota. Kapasitas: 35 penumpang. Sasis: Sasis medium. Panjang: 9,0 m.', 2],
            ['intercity', 'Citouro Low Entry', 'citouro-low-entry', 'Low entry', '9,5 m', 'Citouro Low Entry – Low entry. Kapasitas: 38 penumpang. Sasis: Sasis medium. Panjang: 9,5 m.', 3],
            ['intercity', 'Citouro Shuttle', 'citouro-shuttle', 'Shuttle', '8,0 m', 'Citouro Shuttle – Shuttle. Kapasitas: 28 kursi. Sasis: Sasis medium. Panjang: 8,0 m.', 4],
            ['microbus', 'Mikro S14', 'mikro-s14', 'Shuttle', '6,0 m', 'Mikro S14 – Shuttle. Kapasitas: 14 kursi. Sasis: Sasis ringan. Panjang: 6,0 m.', 1],
            ['microbus', 'Mikro S16', 'mikro-s16', 'Travel', '6,3 m', 'Mikro S16 – Travel. Kapasitas: 16 kursi. Sasis: Sasis ringan. Panjang: 6,3 m.', 2],
            ['microbus', 'Mikro S19', 'mikro-s19', 'Shuttle', '6,8 m', 'Mikro S19 – Shuttle. Kapasitas: 19 kursi. Sasis: Sasis ringan. Panjang: 6,8 m.', 3],
            ['microbus', 'Mikro Executive 12', 'mikro-executive-12', 'Executive', '6,0 m', 'Mikro Executive 12 – Executive. Kapasitas: 12 kursi. Sasis: Sasis ringan. Panjang: 6,0 m.', 4],
            ['box-heavy-duty', 'Box Aluminium', 'box-aluminium', 'Box tertutup', '4,3 × 2,0 m', 'Box Aluminium – Box tertutup. Muatan: 3,5 ton. Sasis: Sasis ringan. Dimensi bak: 4,3 × 2,0 m.', 1],
            ['box-heavy-duty', 'Box Besi', 'box-besi', 'Box tertutup', '4,5 × 2,1 m', 'Box Besi – Box tertutup. Muatan: 4,0 ton. Sasis: Sasis ringan. Dimensi bak: 4,5 × 2,1 m.', 2],
            ['box-heavy-duty', 'Wing Box', 'wing-box', 'Wing box', '6,2 × 2,3 m', 'Wing Box – Wing box. Muatan: 8,0 ton. Sasis: Sasis medium. Dimensi bak: 6,2 × 2,3 m.', 3],
            ['box-heavy-duty', 'Bak Terbuka', 'bak-terbuka', 'Bak terbuka', '5,5 × 2,2 m', 'Bak Terbuka – Bak terbuka. Muatan: 5,0 ton. Sasis: Sasis medium. Dimensi bak: 5,5 × 2,2 m.', 4],
        ];

        foreach ($rows as [$catSlug, $name, $slug, $subtitle, $length, $desc, $order]) {
            $cat = Category::where('slug', $catSlug)->first();
            if (! $cat) {
                continue;
            }
            ProductModel::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $cat->id, 'name' => $name, 'subtitle' => $subtitle, 'brand' => 'NEW ARMADA',
                    'length' => $length, 'description' => $desc, 'is_active' => true, 'sort_order' => $order,
                ]
            );
        }
    }
}
