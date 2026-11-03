<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route sudah dilindungi middleware auth
    }

    /** Terima koordinat dengan koma desimal ("-7,495") dan ubah jadi titik. */
    protected function prepareForValidation(): void
    {
        foreach (['lat', 'lng'] as $k) {
            if ($this->has($k)) {
                $this->merge([$k => str_replace(',', '.', trim((string) $this->input($k)))]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:100'],
            'name_en'    => ['nullable', 'string', 'max:100'],
            'name_zh'    => ['nullable', 'string', 'max:100'],
            'address'    => ['required', 'string', 'max:500'],
            'address_en' => ['nullable', 'string', 'max:500'],
            'address_zh' => ['nullable', 'string', 'max:500'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'maps_url'   => ['nullable', 'url:http,https', 'max:500'],
            'lat'        => ['required', 'numeric', 'between:-90,90'],
            'lng'        => ['required', 'numeric', 'between:-180,180'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'Nama plant',
            'name_en'    => 'Nama plant (EN)',
            'name_zh'    => 'Nama plant (中文)',
            'address'    => 'Alamat',
            'address_en' => 'Alamat (EN)',
            'address_zh' => 'Alamat (中文)',
            'phone'      => 'Telepon',
            'maps_url'   => 'Link Google Maps',
            'lat'        => 'Latitude',
            'lng'        => 'Longitude',
            'sort_order' => 'Urutan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'url'      => ':attribute harus berupa link lengkap yang diawali https://',
            'numeric'  => ':attribute harus berupa angka, contoh -7.4950 (pakai titik).',
            'between'  => ':attribute di luar rentang yang benar.',
            'max'      => ':attribute terlalu panjang.',
        ];
    }
}
