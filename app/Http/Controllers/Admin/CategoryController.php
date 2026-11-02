<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Tambah / ubah / hapus kategori produk (Big Bus, Medium Bus, dst). */
class CategoryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules(), [], $this->attributes());

        unset($data['image']);
        $data['color_bg']   = $data['color_bg'] ?? '#12274d';
        $data['slug']       = $this->uniqueSlug($data['name']);
        $data['sort_order'] = (int) Category::max('sort_order') + 1;
        $data['is_active']  = true;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate($this->rules(), [], $this->attributes());

        unset($data['image']);
        $data['color_bg'] = $data['color_bg'] ?? '#12274d';
        // slug sengaja tidak diubah: dipakai di alamat website (/produk?kategori=big-bus)

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggle(Category $category): JsonResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        return response()->json(['is_active' => $category->is_active]);
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Database ikut menghapus model & galeri (cascade), tapi file fotonya harus dihapus manual.
        foreach ($category->models()->with('galleries')->get() as $model) {
            foreach ($model->galleries as $g) {
                Storage::disk('public')->delete($g->image_path);
            }
            if ($model->image_path) {
                Storage::disk('public')->delete($model->image_path);
            }
        }
        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Kategori dan semua model di dalamnya sudah dihapus.');
    }

    private function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'short_text' => 'nullable|string|max:255',
            'color_bg'   => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:51200',
        ];
    }

    private function attributes(): array
    {
        return [
            'name'       => 'Nama kategori',
            'short_text' => 'Teks singkat',
            'color_bg'   => 'Warna latar',
            'image'      => 'Foto poster',
        ];
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'kategori';
        $slug = $base;
        $i = 2;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
