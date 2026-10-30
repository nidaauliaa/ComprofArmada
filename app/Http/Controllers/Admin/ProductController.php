<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('models')->orderBy('sort_order')->get();
        return view('admin.products.index', compact('categories'));
    }

    public function create()
    {
        // Tampilkan form tambah produk/model
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        // Logika menyimpan data baru ke database
    }

    public function edit($id)
    {
        // Tampilkan form edit berdasarkan $id
        $product = ProductModel::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        // Logika memperbarui data ke database
    }

    public function destroy($id)
    {
        // Logika menghapus data dari database
    }
}
