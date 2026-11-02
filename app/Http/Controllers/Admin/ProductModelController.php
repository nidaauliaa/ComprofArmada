<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ModelGallery;
use App\Models\ProductModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Kelola produk (model bus) di dalam satu kategori, termasuk foto galeri. */
class ProductModelController extends Controller
{
    /** Daftar produk dalam satu kategori. */
    public function index(Category $category)
    {
        $models = $category->models()
            ->with('galleries')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // data untuk form ubah (dibaca JavaScript lewat atribut data-edit)
        $editData = $models->mapWithKeys(fn (ProductModel $m) => [$m->id => [
            'id'          => $m->id,
            'name'        => $m->name,
            'subtitle'    => $m->subtitle,
            'brand'       => $m->brand,
            'height'      => $m->height,
            'width'       => $m->width,
            'length'      => $m->length,
            'description' => $m->description,
            'image'       => $m->image_path ? asset('storage/' . $m->image_path) : null,
            'galleries'   => $m->galleries->map(fn (ModelGallery $g) => [
                'id'  => $g->id,
                'url' => asset('storage/' . $g->image_path),
            ])->values()->all(),
            'update_url'  => route('admin.products.models.update', $m),
        ]])->all();

        return view('admin.products.models', compact('category', 'models', 'editData'));
    }

    public function store(Request $request, Category $category): RedirectResponse
    {
        $data  = $request->validate($this->rules(), [], $this->attributes());
        $files = $request->file('galleries', []);

        unset($data['image'], $data['galleries']);
        $data['brand']       = ($data['brand'] ?? null) ?: 'NEW ARMADA';
        $data['slug']        = $this->uniqueSlug($data['name']);
        $data['category_id'] = $category->id;
        $data['sort_order']  = (int) ProductModel::where('category_id', $category->id)->max('sort_order') + 1;
        $data['is_active']   = true;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $model = ProductModel::create($data);
        $this->saveGalleries($model, $files);

        return redirect()->route('admin.products.models.index', $category)
            ->with('success', 'Produk "' . $model->name . '" berhasil ditambahkan.');
    }

    public function update(Request $request, ProductModel $productModel): RedirectResponse
    {
        $data  = $request->validate($this->rules(), [], $this->attributes());
        $files = $request->file('galleries', []);

        unset($data['image'], $data['galleries']);
        $data['brand'] = ($data['brand'] ?? null) ?: 'NEW ARMADA';
        // slug sengaja tidak diubah: dipakai di alamat website (/produk/tourista-25)

        if ($request->hasFile('image')) {
            if ($productModel->image_path) {
                Storage::disk('public')->delete($productModel->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $productModel->update($data);
        $this->saveGalleries($productModel, $files);

        return redirect()->route('admin.products.models.index', $productModel->category_id)
            ->with('success', 'Produk "' . $productModel->name . '" berhasil diperbarui.');
    }

    public function toggle(ProductModel $productModel): JsonResponse
    {
        $productModel->update(['is_active' => ! $productModel->is_active]);

        return response()->json(['is_active' => $productModel->is_active]);
    }

    public function destroy(ProductModel $productModel): RedirectResponse
    {
        $categoryId = $productModel->category_id;
        $name = $productModel->name;

        foreach ($productModel->galleries as $g) {
            Storage::disk('public')->delete($g->image_path);
        }
        if ($productModel->image_path) {
            Storage::disk('public')->delete($productModel->image_path);
        }

        $productModel->delete();

        return redirect()->route('admin.products.models.index', $categoryId)
            ->with('success', 'Produk "' . $name . '" sudah dihapus.');
    }

    /** Hapus satu foto galeri (dipanggil lewat fetch dari form ubah). */
    public function destroyGallery(ModelGallery $gallery): JsonResponse
    {
        Storage::disk('public')->delete($gallery->image_path);
        $gallery->delete();

        return response()->json(['deleted' => true]);
    }

    private function saveGalleries(ProductModel $model, array $files): void
    {
        $order = (int) ModelGallery::where('product_model_id', $model->id)->max('sort_order');

        foreach ($files as $file) {
            ModelGallery::create([
                'product_model_id' => $model->id,
                'image_path'       => $file->store('galleries', 'public'),
                'sort_order'       => ++$order,
            ]);
        }
    }

    private function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'brand'       => 'nullable|string|max:255',
            'height'      => 'nullable|string|max:255',
            'width'       => 'nullable|string|max:255',
            'length'      => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:51200',
            'galleries'   => 'nullable|array|max:20',
            'galleries.*' => 'image|mimes:jpeg,png,jpg,webp|max:51200',
        ];
    }

    private function attributes(): array
    {
        return [
            'name'        => 'Nama produk',
            'subtitle'    => 'Subjudul',
            'brand'       => 'Merek',
            'height'      => 'Tinggi',
            'width'       => 'Lebar',
            'length'      => 'Panjang',
            'description' => 'Deskripsi',
            'image'       => 'Foto utama',
            'galleries'   => 'Foto galeri',
            'galleries.*' => 'Foto galeri',
        ];
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;
        $i = 2;

        while (ProductModel::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
