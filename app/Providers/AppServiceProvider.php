<?php

namespace App\Providers;

use App\Models\Setting;
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
    }
}
