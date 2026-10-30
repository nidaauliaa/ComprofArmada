@extends('layouts.admin')

@section('title', 'Beranda')
@section('page_icon', 'home')
@section('page_title', 'Beranda')


@section('content')
@php
    $has = fn ($route) => \Illuminate\Support\Facades\Route::has($route);

    $bannerCount   = \App\Models\Banner::where('is_active', 1)->count();
    $newsCount     = \App\Models\News::where('is_active', 1)->count();
    $newsNoThumb   = \App\Models\News::where('is_active', 1)->whereNull('thumbnail_path')->count();
    $newsNoEnglish = \App\Models\News::where('is_active', 1)->where(function ($q) {
        $q->whereNull('title->en')->orWhere('title->en', '');
    })->count();
@endphp
    <h1>Selamat datang</h1>
    <p class="mu">Ubah konten di sini, website langsung ikut berubah.</p>

    <div class="qa">
        <a href="{{ $has('admin.banners.index') ? route('admin.banners.index') : '#' }}"><svg class="i"><use href="#i-image"/></svg>Ubah banner</a>
        <a href="#"><svg class="i"><use href="#i-bus"/></svg>Kelola produk</a>
        <a href="{{ $has('admin.news.index') ? route('admin.news.index', ['tambah' => 1]) : '#' }}"><svg class="i"><use href="#i-news"/></svg>Tulis berita</a>
        <a href="#"><svg class="i"><use href="#i-pin"/></svg>Atur lokasi</a>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Ringkasan</h3>
        <div class="st">
            <div><b>{{ $bannerCount }}</b>Banner tayang</div>
            {{-- TODO: ganti angka di bawah dengan count() dari model masing-masing setelah modelnya dibuat --}}
            <div><b>20</b>Model tayang</div>
            <div><b>{{ $newsCount }}</b>Berita tayang</div>
            <div><b>3</b>Lokasi tayang</div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Perlu dilengkapi</h3>
        @if ($newsNoThumb === 0 && $newsNoEnglish === 0)
            <p><span class="bdg">✓</span> Semua konten sudah lengkap.</p>
        @endif
        @if ($newsNoThumb > 0)
            <p><span class="bdg" style="background:#ffedd5;color:#9a3412">!</span> {{ $newsNoThumb }} berita belum punya thumbnail
                @if ($has('admin.news.index')) · <a href="{{ route('admin.news.index') }}" style="color:var(--pr);font-weight:600">Lengkapi</a>@endif</p>
        @endif
        @if ($newsNoEnglish > 0)
            <p><span class="bdg" style="background:#ffedd5;color:#9a3412">!</span> {{ $newsNoEnglish }} berita belum ada judul bahasa Inggris
                @if ($has('admin.news.index')) · <a href="{{ route('admin.news.index') }}" style="color:var(--pr);font-weight:600">Lengkapi</a>@endif</p>
        @endif
    </div>
@endsection
