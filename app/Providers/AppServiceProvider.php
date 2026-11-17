<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\ModelGallery;
use App\Models\News;
use App\Models\Plant;
use App\Models\ProductModel;
use App\Models\SalesContact;
use App\Models\Setting;
use App\Models\SocialPost;
use App\Services\ContentApi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pengaturan Situs (logo, alamat, telepon, sosmed, SEO) tersedia sebagai $site di halaman publik
        View::composer(['welcome', 'search', 'contact', 'product-category', 'product-detail', 'news.*', 'partials.*'], function ($view) {
            $view->with('site', Setting::allCached());
        });

        // API Management: cache JSON dibersihkan otomatis setiap konten diubah/dihapus di admin
        foreach ([Banner::class, News::class, Category::class, ProductModel::class, ModelGallery::class, Plant::class, SocialPost::class, SalesContact::class, Setting::class] as $model) {
            $model::saved(fn () => ContentApi::flush());
            $model::deleted(fn () => ContentApi::flush());
        }
    }
}
