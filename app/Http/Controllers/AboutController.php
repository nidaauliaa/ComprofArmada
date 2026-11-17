<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;

/** Halaman publik "Tentang Kami". */
class AboutController extends Controller
{
    public function __invoke()
    {
        return view('about', ['about' => AboutPage::current()]);
    }
}
