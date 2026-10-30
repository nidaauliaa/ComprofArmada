<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /** Data awal dari prototype. Aman dijalankan berulang (tidak membuat dobel). */
    public function run(): void
    {
        $rows = [
            ['name' => 'Magelang (HQ)', 'address' => 'Jl. Mayjen Bambang Soegeng No. 7, Mertoyudan, Magelang, Jawa Tengah', 'phone' => '(0293) 364371', 'lat' => -7.4950, 'lng' => 110.2200],
            ['name' => 'Tambun', 'address' => 'Jl. Raya Diponegoro KM. 38 No. 107, Setiamekar, Jatimulya, Tambun Selatan, Kabupaten Bekasi, Jawa Barat.', 'phone' => '(021) 88346911', 'lat' => -6.261878333448183, 'lng' => 107.05070225889548],
            ['name' => 'Jakarta Utara', 'address' => 'Jl. Pegangsaan Dua KM.3,5, No.17, RT.13/RW.3, Pegangsaan Dua, Kec. Klp. Gading, Jkt Utara, Daerah Khusus Ibukota Jakarta 14250', 'phone' => '081938551138', 'lat' => -6.153745868389947, 'lng' => 106.91646063785107],
        ];

        foreach ($rows as $i => $r) {
            Plant::firstOrCreate(['name' => $r['name']], $r + ['is_active' => true, 'sort_order' => $i + 1]);
        }
    }
}
