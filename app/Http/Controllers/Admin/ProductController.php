<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

/** Halaman daftar kategori produk. Kelola kategori ada di CategoryController, kelola model/produk di ProductModelController. */
class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('models')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // data untuk form ubah (dibaca JavaScript lewat atribut data-edit)
        $editData = $categories->mapWithKeys(fn (Category $c) => [$c->id => [
            'id'         => $c->id,
            'name'       => $c->name,
            'name_en'    => $c->name_en,
            'name_zh'    => $c->name_zh,
            'short_text' => $c->short_text,
            'color_bg'   => $c->color_bg ?: '#12274d',
            'image'      => $c->image_path ? asset('storage/' . $c->image_path) : null,
            'update_url' => route('admin.products.categories.update', $c),
        ]])->all();

        return view('admin.products.index', compact('categories', 'editData'));
    }
}
