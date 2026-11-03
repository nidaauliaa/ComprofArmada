<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use Translatable;

    protected $fillable = [
        'name', 'name_en', 'name_zh', 'address', 'address_en', 'address_zh',
        'phone', 'maps_url', 'lat', 'lng', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'lat'        => 'float',
        'lng'        => 'float',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Plant yang boleh tampil di website. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Data untuk peta di halaman depan (dibaca JavaScript). */
    public function toMapArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->t('name'),
            'addr'  => $this->t('address'),
            'phone' => $this->phone,
            'maps'  => $this->maps_url,
            'll'    => [$this->lat, $this->lng],
        ];
    }

    /** Data untuk mengisi form "Ubah lokasi" di admin (dibaca JavaScript). */
    public function toEditArray(): array
    {
        return [
            'id'         => $this->id,
            'update_url' => route('admin.plants.update', $this),
            'name'       => $this->name,
            'name_en'    => $this->name_en,
            'name_zh'    => $this->name_zh,
            'address'    => $this->address,
            'address_en' => $this->address_en,
            'address_zh' => $this->address_zh,
            'phone'      => $this->phone,
            'maps_url'   => $this->maps_url,
            'lat'        => $this->lat,
            'lng'        => $this->lng,
            'sort_order' => $this->sort_order,
            'is_active'  => $this->is_active,
        ];
    }
}
