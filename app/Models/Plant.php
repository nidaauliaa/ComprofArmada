<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'maps_url', 'lat', 'lng', 'is_active', 'sort_order'];

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
            'name'  => $this->name,
            'addr'  => $this->address,
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
            'address'    => $this->address,
            'phone'      => $this->phone,
            'maps_url'   => $this->maps_url,
            'lat'        => $this->lat,
            'lng'        => $this->lng,
            'sort_order' => $this->sort_order,
            'is_active'  => $this->is_active,
        ];
    }
}
