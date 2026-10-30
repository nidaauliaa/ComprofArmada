<?php

namespace App\Http\Requests\Admin;

use App\Models\News;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route sudah dilindungi middleware auth
    }

    public function rules(): array
    {
        return [
            'title'                    => ['required', 'array'],
            'title.id'                 => ['required', 'string', 'max:200'],
            'title.en'                 => ['nullable', 'string', 'max:200'],
            'title.zh'                 => ['nullable', 'string', 'max:200'],
            'category'                 => ['required', Rule::in(News::CATEGORIES)],
            'published_at'             => ['required', 'date'],
            'is_active'                => ['nullable', 'boolean'],
            'thumbnail'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_thumbnail'         => ['nullable', 'boolean'],

            'blocks'                   => ['nullable', 'array', 'max:60'],
            'blocks.*.type'            => ['required', Rule::in(['p', 'i'])],
            'blocks.*.text.*'          => ['nullable', 'string', 'max:20000'],
            'blocks.*.caption.*'       => ['nullable', 'string', 'max:255'],
            'blocks.*.existing_image'  => ['nullable', 'string', 'max:255'],
            'blocks.*.image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title.id'       => 'Judul (ID)',
            'title.en'       => 'Judul (EN)',
            'title.zh'       => 'Judul (中文)',
            'category'       => 'Kategori',
            'published_at'   => 'Tanggal',
            'thumbnail'      => 'Thumbnail',
            'blocks.*.image' => 'Gambar artikel',
        ];
    }

    public function messages(): array
    {
        return [
            'required'  => ':attribute wajib diisi.',
            'image'     => ':attribute harus berupa gambar.',
            'mimes'     => ':attribute harus berformat JPG, PNG, atau WebP.',
            'max.file'  => ':attribute maksimal 4 MB.',
            'max.string'=> ':attribute terlalu panjang.',
        ];
    }
}
