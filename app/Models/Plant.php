<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use Translatable;

    protected $fillable = [
        'name', 'name_en', 'name_zh', 'address', 'address_en', 'address_zh',
        'phone', 'phones', 'email', 'working_hours', 'working_hours_en', 'working_hours_zh',
        'maps_url', 'lat', 'lng', 'is_active', 'is_hq', 'sort_order',
    ];

    protected $casts = [
        'lat'        => 'float',
        'lng'        => 'float',
        'phones'     => 'array',
        'is_active'  => 'boolean',
        'is_hq'      => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Plant yang boleh tampil di website. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Kantor pusat untuk halaman /kontak.
     * Yang bertanda HQ dipakai walau disembunyikan dari peta; kalau belum ada, pakai plant tayang pertama.
     */
    public static function headquarters(): ?self
    {
        return static::where('is_hq', true)->orderBy('sort_order')->orderBy('id')->first()
            ?? static::active()->orderBy('sort_order')->orderBy('id')->first();
    }

    /** Daftar nomor telepon (tanpa yang kosong). Jatuh ke kolom lama "phone" bila daftar kosong. */
    public function phoneList(): array
    {
        $list = array_values(array_filter(
            array_map(fn ($v) => trim((string) $v), $this->phones ?? []),
            fn ($v) => $v !== ''
        ));

        return $list ?: ($this->phone ? [trim($this->phone)] : []);
    }

    /** Nomor telepon beserta link tel: untuk ditampilkan di halaman Kontak. */
    public function phoneLinks(): array
    {
        return array_map(fn ($p) => [
            'label' => $p,
            'href'  => 'tel:' . preg_replace('/[^0-9+]/', '', $p),
        ], $this->phoneList());
    }

    /** Data untuk peta di halaman depan (dibaca JavaScript). */
    public function toMapArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->t('name'),
            'addr'  => $this->t('address'),
            'phone' => implode(' / ', $this->phoneList()) ?: null,
            'maps'  => $this->maps_url,
            'll'    => [$this->lat, $this->lng],
        ];
    }

    /** Data untuk mengisi form "Ubah lokasi" di admin (dibaca JavaScript). */
    public function toEditArray(): array
    {
        return [
            'id'               => $this->id,
            'update_url'       => route('admin.plants.update', $this),
            'name'             => $this->name,
            'name_en'          => $this->name_en,
            'name_zh'          => $this->name_zh,
            'address'          => $this->address,
            'address_en'       => $this->address_en,
            'address_zh'       => $this->address_zh,
            'phones'           => $this->phoneList(),
            'email'            => $this->email,
            'working_hours'    => $this->working_hours,
            'working_hours_en' => $this->working_hours_en,
            'working_hours_zh' => $this->working_hours_zh,
            'maps_url'         => $this->maps_url,
            'lat'              => $this->lat,
            'lng'              => $this->lng,
            'sort_order'       => $this->sort_order,
            'is_active'        => $this->is_active,
            'is_hq'            => $this->is_hq,
        ];
    }
}
