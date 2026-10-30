<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductFrontendController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->query('kategori', 'medium-bus');

        // Ubah format slug jadi nama kategori yang rapi
        $categories = [
            'big-bus' => 'Big Bus',
            'medium-bus' => 'Medium Bus',
            'intercity' => 'Intercity',
            'microbus' => 'Microbus',
            'box-heavy-duty' => 'Box & Heavy Duty'
        ];

        $currentCategoryName = $categories[$kategori] ?? 'Medium Bus';

        return view('product-category', compact('kategori', 'currentCategoryName'));
    }

    public function show($slug)
    {
        // Di sini nantinya bisa mengambil data dari database berdasarkan slug produk
        return view('product-detail', compact('slug'));
    }
}
