@extends('layouts.admin')

@section('title', 'Beranda')
@section('page_icon', 'home')
@section('page_title', 'Beranda')

@section('content')
@php
    $has = fn ($route) => \Illuminate\Support\Facades\Route::has($route);

    $bannerCount = \App\Models\Banner::where('is_active', 1)->count();
    $newsCount = \App\Models\News::where('is_active', 1)->count();
    $newsNoThumb = \App\Models\News::where('is_active', 1)->whereNull('thumbnail_path')->count();
    $plantCount = \App\Models\Plant::where('is_active', 1)->count();
    $modelCount = \App\Models\ProductModel::where('is_active', 1)
        ->whereHas('category', fn ($query) => $query->where('is_active', 1))
        ->count();
    $newsNoEnglish = \App\Models\News::where('is_active', 1)->where(function ($query) {
        $query->whereNull('title->en')->orWhere('title->en', '');
    })->count();
@endphp

    <div class="ph">
        <div>
            <h1>Dashboard</h1>
            <p>Pantau konten yang tayang dan mulai pekerjaan dari satu tempat. Setiap perubahan langsung digunakan oleh website publik.</p>
        </div>
        <x-admin-view-page :href="url('/')" page="Beranda" />
    </div>

    <div class="qa" aria-label="Aksi cepat">
        <a href="{{ $has('admin.banners.index') ? route('admin.banners.index') : '#' }}">
            <svg class="i" aria-hidden="true"><use href="#i-image"/></svg>
            <span>Kelola banner</span>
        </a>
        <a href="{{ $has('admin.products.index') ? route('admin.products.index') : '#' }}">
            <svg class="i" aria-hidden="true"><use href="#i-bus"/></svg>
            <span>Kelola produk</span>
        </a>
        <a href="{{ $has('admin.news.index') ? route('admin.news.index', ['tambah' => 1]) : '#' }}">
            <svg class="i" aria-hidden="true"><use href="#i-news"/></svg>
            <span>Tulis berita</span>
        </a>
        <a href="{{ $has('admin.plants.index') ? route('admin.plants.index') : '#' }}">
            <svg class="i" aria-hidden="true"><use href="#i-pin"/></svg>
            <span>Atur lokasi</span>
        </a>
    </div>

    <div class="dash-layout">
        <section class="card">
            <h3>Ringkasan konten</h3>
            <div class="st">
                <div><b>{{ $bannerCount }}</b>Banner tayang</div>
                <div><b>{{ $modelCount }}</b>Model tayang</div>
                <div><b>{{ $newsCount }}</b>Berita tayang</div>
                <div><b>{{ $plantCount }}</b>Lokasi tayang</div>
            </div>
        </section>

        <section class="card">
            <h3>Perlu dilengkapi</h3>

            @if ($newsNoThumb === 0 && $newsNoEnglish === 0)
                <div class="dash-complete">
                    <svg class="i" aria-hidden="true"><use href="#i-check"/></svg>
                    Semua konten sudah lengkap.
                </div>
            @else
                <div class="dash-alerts">
                    @if ($newsNoThumb > 0)
                        <div class="dash-alert">
                            <svg class="i" aria-hidden="true"><use href="#i-image"/></svg>
                            <span>{{ $newsNoThumb }} berita belum punya thumbnail.</span>
                            @if ($has('admin.news.index'))
                                <a href="{{ route('admin.news.index') }}">Lengkapi</a>
                            @endif
                        </div>
                    @endif

                    @if ($newsNoEnglish > 0)
                        <div class="dash-alert">
                            <svg class="i" aria-hidden="true"><use href="#i-text"/></svg>
                            <span>{{ $newsNoEnglish }} berita belum memiliki judul bahasa Inggris.</span>
                            @if ($has('admin.news.index'))
                                <a href="{{ route('admin.news.index') }}">Lengkapi</a>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </section>
    </div>
@endsection
