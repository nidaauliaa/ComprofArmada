<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\ProductFrontendController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\NewsController;
use App\Models\News;
use App\Http\Controllers\NewsPageController;


Route::get('/', function () {
    $news = News::published()
        ->orderByDesc('published_at')
        ->take(8)
        ->get();

    return view('welcome', compact('news'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/produk', [ProductFrontendController::class, 'index'])->name('product.index');
Route::get('/produk/{slug}', [ProductFrontendController::class, 'show'])->name('product.show');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Banner
    Route::resource('banners', BannerController::class);
    Route::post('banners/{banner}/toggle', [BannerController::class, 'toggle'])
        ->name('banners.toggle');

    // Produk
    Route::resource('products', AdminProductController::class);

    // Berita & Event
    Route::resource('news', NewsController::class)
        ->except(['create', 'edit', 'show']);

    Route::patch('news/{news}/toggle', [NewsController::class, 'toggle'])
        ->name('news.toggle');

    Route::patch('news/{news}/thumbnail', [NewsController::class, 'thumbnail'])
        ->name('news.thumbnail');

});

// di bagian route publik (di luar grup admin):
Route::get('/berita', [NewsPageController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsPageController::class, 'show'])->name('news.show');

require __DIR__.'/auth.php';

