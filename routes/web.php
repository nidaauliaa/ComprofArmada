<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\ProductFrontendController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductModelController;
use App\Http\Controllers\Admin\NewsController;
use App\Models\News;
use App\Http\Controllers\NewsPageController;
use App\Http\Controllers\Admin\PlantController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SocialFeedController;
use App\Http\Controllers\Admin\SocialPostController;
use App\Http\Controllers\Admin\SettingController;


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

Route::view('/kontak', 'contact')->name('contact');

// Bahasa (ID/EN/中文), pencarian, JSON sosial media
Route::get('/bahasa/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
Route::get('/cari', [SearchController::class, 'index'])->name('search');
Route::get('/cari/saran', [SearchController::class, 'suggest'])->middleware('throttle:60,1')->name('search.suggest');
Route::get('/api/social', [SocialFeedController::class, 'index'])->middleware('throttle:60,1')->name('api.social');

Route::get('/produk', [ProductFrontendController::class, 'index'])->name('product.index');
Route::get('/produk/{slug}', [ProductFrontendController::class, 'show'])->name('product.show');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Banner
    Route::resource('banners', BannerController::class);
    Route::post('banners/{banner}/toggle', [BannerController::class, 'toggle'])
        ->name('banners.toggle');

    // Produk: daftar kategori
    Route::get('products', [AdminProductController::class, 'index'])->name('products.index');

    // Kategori (tambah / ubah / hapus / tayang-sembunyi)
    Route::post('products/categories', [CategoryController::class, 'store'])->name('products.categories.store');
    Route::put('products/categories/{category}', [CategoryController::class, 'update'])->name('products.categories.update');
    Route::patch('products/categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('products.categories.toggle');
    Route::delete('products/categories/{category}', [CategoryController::class, 'destroy'])->name('products.categories.destroy');

    // Produk / model di dalam satu kategori
    Route::get('products/categories/{category}', [ProductModelController::class, 'index'])->name('products.models.index');
    Route::post('products/categories/{category}/models', [ProductModelController::class, 'store'])->name('products.models.store');
    Route::put('products/models/{productModel}', [ProductModelController::class, 'update'])->name('products.models.update');
    Route::patch('products/models/{productModel}/toggle', [ProductModelController::class, 'toggle'])->name('products.models.toggle');
    Route::delete('products/models/{productModel}', [ProductModelController::class, 'destroy'])->name('products.models.destroy');
    Route::delete('products/galleries/{gallery}', [ProductModelController::class, 'destroyGallery'])->name('products.galleries.destroy');


    // Berita & Event
    Route::resource('news', NewsController::class)
        ->except(['create', 'edit', 'show']);

    Route::patch('news/{news}/toggle', [NewsController::class, 'toggle'])
        ->name('news.toggle');

    Route::patch('news/{news}/thumbnail', [NewsController::class, 'thumbnail'])
        ->name('news.thumbnail');

    // Lokasi
    Route::resource('plants', PlantController::class)
        ->except(['create', 'edit', 'show']);
    Route::patch('plants/{plant}/toggle', [PlantController::class, 'toggle'])
        ->name('plants.toggle');

    // Instagram & Sosial Media (link share / kode embed oEmbed / JSON -> tampil di Home)
    Route::get('social', [SocialPostController::class, 'index'])->name('social.index');
    Route::post('social', [SocialPostController::class, 'store'])->name('social.store');
    Route::put('social/{post}', [SocialPostController::class, 'update'])->name('social.update');
    Route::patch('social/{post}/toggle', [SocialPostController::class, 'toggle'])->name('social.toggle');
    Route::patch('social/{post}/move/{dir}', [SocialPostController::class, 'move'])->whereIn('dir', ['up', 'down'])->name('social.move');
    Route::delete('social/{post}', [SocialPostController::class, 'destroy'])->name('social.destroy');

    // Pengaturan Situs
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

});

// di bagian route publik (di luar grup admin):
Route::get('/berita', [NewsPageController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsPageController::class, 'show'])->name('news.show');


require __DIR__.'/auth.php';

