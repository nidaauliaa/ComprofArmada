@extends('layouts.admin')

@section('title', 'Kontak Sales')
@section('page_icon', 'phone')
@section('page_title', 'Kontak Sales')

@push('styles')
{{-- memakai gaya yang sama dengan halaman Berita / Lokasi Plant --}}
<link rel="stylesheet" href="{{ asset('css/admin-news.css') }}?v={{ @filemtime(public_path('css/admin-news.css')) }}">
<style>
    .sc-ok{background:#dcfce7;color:#166534;padding:10px 14px;border-radius:8px;margin-bottom:16px}
    .sc-add{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;padding:18px;margin-bottom:20px;border:1px solid #e5e7eb;border-radius:12px;background:#fff}
    .sc-add label{display:flex;flex-direction:column;gap:6px;font-weight:600;font-size:14px;flex:1;min-width:180px}
    .sc-add label.sc-sm{flex:0 0 100px;min-width:100px}
    .sc-in,.sc-add input,.sc-f input{border:1px solid #d1d5db;border-radius:8px;padding:9px 12px;font:inherit;background:#fff;margin:0}
    .sc-f{display:flex;gap:8px;flex:1;flex-wrap:wrap;min-width:0}
    .sc-f input{flex:1;min-width:140px}
    .sc-f input.sc-n{flex:0 0 80px;min-width:80px}
    .sc-st{font-size:13px;padding:3px 10px;border-radius:999px;white-space:nowrap}
    .sc-st.on{background:#dcfce7;color:#166534}
    .sc-st.off{background:#e5e7eb;color:#4b5563}
    .sc-inline{margin:0}
    .sc-texts{padding:20px;margin-top:28px;border:1px solid #e5e7eb;border-radius:12px;background:#fff}
    .sc-texts h3{margin:0 0 4px;font-size:16px}
    .sc-texts .sc-hint{margin:0 0 16px;color:#64748b;font-size:14px}
    .sc-texts .lt-label{font-size:14px}
    .sc-texts .lt-pane input,.sc-texts .lt-pane textarea{width:100%;box-sizing:border-box;border:1px solid #d1d5db;border-radius:8px;padding:9px 12px;font:inherit;background:#fff}
    .sc-texts .lt-pane textarea{resize:vertical}
</style>
@endpush

@section('content')
    @if (session('status'))
        <div class="sc-ok">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <b>Belum tersimpan:</b>
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="ph">
        <div>
            <h1>Kontak Sales</h1>
            <p class="mu">Nomor WhatsApp yang tampil di halaman Kontak website. Nomor boleh diawali 0812… atau +62 812…, link WhatsApp dibuat otomatis.</p>
        </div>
    </div>

    {{-- Tambah kontak --}}
    <form method="POST" action="{{ route('admin.sales.store') }}" class="sc-add">
        @csrf
        <label>Nama sales
            <input type="text" name="name" maxlength="100" required value="{{ old('name') }}" placeholder="Contoh: Budi Santoso">
        </label>
        <label>Nomor WhatsApp
            <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="+62 812 3456 7890">
        </label>
        <label class="sc-sm">Urutan
            <input type="number" name="sort_order" min="0" max="9999" placeholder="Auto">
        </label>
        <button type="submit" class="b"><svg class="i"><use href="#i-plus"/></svg> Tambah</button>
    </form>

    {{-- Daftar kontak --}}
    @forelse ($contacts as $c)
        <div class="row">
            <span class="ri"><svg class="i"><use href="#i-phone"/></svg></span>

            <div class="sc-f">
                <input form="edit-{{ $c->id }}" type="text" name="name" value="{{ $c->name }}" maxlength="100" required aria-label="Nama sales">
                <input form="edit-{{ $c->id }}" type="tel" name="phone" value="{{ $c->phone }}" required aria-label="Nomor WhatsApp">
                <input form="edit-{{ $c->id }}" type="number" name="sort_order" value="{{ $c->sort_order }}" min="0" max="9999" class="sc-n" title="Urutan tampil" aria-label="Urutan">
            </div>

            <span class="sc-st {{ $c->is_active ? 'on' : 'off' }}">{{ $c->is_active ? 'Tampil' : 'Disembunyikan' }}</span>

            <form id="edit-{{ $c->id }}" method="POST" action="{{ route('admin.sales.update', $c) }}" class="sc-inline">
                @csrf @method('PUT')
                <button type="submit" class="b s">Simpan</button>
            </form>

            <form method="POST" action="{{ route('admin.sales.toggle', $c) }}" class="sc-inline">
                @csrf @method('PATCH')
                <button type="submit" class="b o s">{{ $c->is_active ? 'Sembunyikan' : 'Tampilkan' }}</button>
            </form>

            <form method="POST" action="{{ route('admin.sales.destroy', $c) }}" class="sc-inline" onsubmit="return confirm('Hapus kontak ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="b o s"><svg class="i"><use href="#i-trash"/></svg> Hapus</button>
            </form>
        </div>
    @empty
        <div class="emp">Belum ada kontak sales. Isi form di atas untuk menambah.</div>
    @endforelse

    {{-- Teks halaman Kontak: terjemahan diisi manual (ID | EN | 中文) --}}
    
@endsection