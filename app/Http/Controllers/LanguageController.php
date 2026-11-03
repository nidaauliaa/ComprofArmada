<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        abort_unless(array_key_exists($locale, config('site.locales')), 404);

        $request->session()->put('locale', $locale);

        // kembali ke halaman sebelumnya (hanya bila dari situs ini)
        $back = url()->previous();
        $target = str_starts_with($back, url('/')) ? $back : url('/');

        return redirect()->to($target)->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
}
