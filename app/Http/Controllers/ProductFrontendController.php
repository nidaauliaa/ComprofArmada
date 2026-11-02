<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductFrontendController extends Controller
{
    /** Halaman produk: tab kategori + daftar model di kategori terpilih (?kategori=slug). */
    public function index(Request $request)
    {
        $categories = $this->activeCategories();

        $category = $categories->firstWhere('slug', $request->query('kategori')) ?? $categories->first();

        $models = $category
            ? $category->models()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
            : collect();

        return view('product-category', compact('categories', 'category', 'models'));
    }

    /** Halaman detail satu produk (/produk/{slug}). */
    public function show(string $slug)
    {
        $model = ProductModel::with(['category', 'galleries'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->firstOrFail();

        $category   = $model->category;
        $categories = $this->activeCategories();

        return view('product-detail', compact('model', 'category', 'categories'));
    }

    private function activeCategories()
    {
        return Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
