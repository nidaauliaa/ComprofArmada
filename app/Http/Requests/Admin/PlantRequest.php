<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route sudah dilindungi middleware auth
    }

    /** Rapikan koordinat (koma jadi titik) dan daftar nomor telepon (buang baris kosong). */
    protected function prepareForValidation(): void
    {
        foreach (['lat', 'lng'] as $k) {
            if ($this->has($k)) {
                $this->merge([$k => str_replace(',', '.', trim((string) $this->input($k)))]);
            }
        }

        $phones = array_values(array_filter(
            array_map(fn ($v) => trim((string) $v), (array) $this->input('phones', [])),
            fn ($v) => $v !== ''
        ));
        $this->merge(['phones' => $phones]);
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:100'],
            'name_en'          => ['nullable', 'string', 'max:100'],
            'name_zh'          => ['nullable', 'string', 'max:100'],
            'address'          => ['required', 'string', 'max:500'],
            'address_en'       => ['nullable', 'string', 'max:500'],
            'address_zh'       => ['nullable', 'string', 'max:500'],
            'phones'           => ['nullable', 'array', 'max:10'],
            'phones.*'         => ['string', 'max:50'],
            'email'            => ['nullable', 'email', 'max:150'],
            'working_hours'    => ['nullable', 'string', 'max:255'],
            'working_hours_en' => ['nullable', 'string', 'max:255'],
            'working_hours_zh' => ['nullable', 'string', 'max:255'],
            'maps_url'         => ['nullable', 'url:http,https', 'max:500'],
            'lat'              => ['required', 'numeric', 'between:-90,90'],
            'lng'              => ['required', 'numeric', 'between:-180,180'],
            'sort_order'       => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'        => ['nullable', 'boolean'],
            'is_hq'            => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'Nama plant',
            'name_en'          => 'Nama plant (EN)',
            'name_zh'          => 'Nama plant (中文)',
            'address'          => 'Alamat',
            'address_en'       => 'Alamat (EN)',
            'address_zh'       => 'Alamat (中文)',
            'phones'           => 'Telepon',
            'phones.*'         => 'Nomor telepon',
            'email'            => 'Email',
            'working_hours'    => 'Jam kerja',
            'working_hours_en' => 'Jam kerja (EN)',
            'working_hours_zh' => 'Jam kerja (中文)',
            'maps_url'         => 'Link Google Maps',
            'lat'              => 'Latitude',
            'lng'              => 'Longitude',
            'sort_order'       => 'Urutan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'url'      => ':attribute harus berupa link lengkap yang diawali https://',
            'email'    => ':attribute harus berupa alamat email yang benar.',
            'numeric'  => ':attribute harus berupa angka, contoh -7.4950 (pakai titik).',
            'between'  => ':attribute di luar rentang yang benar.',
            'max'      => ':attribute terlalu panjang.',
        ];
    }
}
