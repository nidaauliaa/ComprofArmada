<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/** Kategori + model produk contoh sekaligus:  php artisan db:seed --class=CatalogSeeder */
class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([CategorySeeder::class, ProductModelSeeder::class]);
    }
}
