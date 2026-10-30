<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $casts = [
        'name' => 'array', // Otomatis membaca struktur JSON multi-bahasa dari database
    ];

    // Helper untuk mengambil bahasa yang aktif saat ini
    public function getNameAttribute($value)
    {
        $names = json_decode($value, true);
        $locale = app()->getLocale(); // Mengambil bahasa aktif (id, en, zh)

        return $names[$locale] ?? $names['id'] ?? '';
    }
}
