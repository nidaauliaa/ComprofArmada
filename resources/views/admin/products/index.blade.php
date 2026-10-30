@extends('layouts.admin')

@section('title', 'Produk & Kategori – Panel Admin New Armada')

@section('header-icon')
    <svg class="i" style="width:18px;height:18px"><use href="#i-bus"/></svg>
@endsection

@section('header-title', 'Produk')

@section('styles')
<style>
    .breadcrumb-top { font-size: 13px; color: var(--mu); font-weight: 600; margin-bottom: 6px; }
    .ph { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 28px; }
    .ph h1 { font-size: 28px; margin: 0 0 6px; font-weight: 800; letter-spacing: -.02em; }
    .ph p { color: var(--mu); margin: 0; font-size: 14px; }

    .cat-card-row {
        display: flex; align-items: center; gap: 20px;
        padding: 18px 24px; border: 1px solid var(--ln); border-radius: 16px;
        margin-bottom: 14px; background: var(--cd);
        box-shadow: 0 4px 12px rgba(15,23,42,.03);
        transition: border-color .2s;
    }
    .cat-card-row:hover { border-color: #cbd5e1; }

    .cat-thumb {
        width: 72px; height: 52px; border-radius: 10px; overflow: hidden;
        background: #0b172c; flex: none; display: grid; place-items: center;
        color: #fff; font-size: 20px; border: 1px solid var(--ln);
    }
    .cat-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .cat-details { flex: 1; min-width: 0; }
    .cat-details b { font-size: 17px; display: block; color: var(--tx); font-weight: 700; margin-bottom: 2px; }
    .cat-details small { color: var(--mu); font-size: 13px; font-weight: 500; }

    .cat-actions { display: flex; align-items: center; gap: 14px; flex: none; }

    /* TOGGLE SWITCH */
    .sw { position: relative; width: 44px; height: 24px; flex: none; }
    .sw input { position: absolute; opacity: 0; inset: 0; cursor: pointer; z-index: 1; margin: 0; }
    .sw i { position: absolute; inset: 0; background: #cbd5e1; border-radius: 99px; transition: .2s; }
    .sw i::after { content: ""; position: absolute; left: 3px; top: 3px; width: 18px; height: 18px; border-radius: 50%; background: #fff; transition: .2s; box-shadow: 0 2px 4px rgba(0,0,0,.15); }
    .sw input:checked + i { background: #16a34a; }
    .sw input:checked + i::after { left: 23px; }

    .btn-action {
        display: inline-flex; align-items: center; gap: 6px;
        background: transparent; color: var(--tx); border: 1px solid var(--ln);
        padding: 8px 14px; border-radius: 8px; font: 600 13px var(--bd);
        cursor: pointer; transition: .2s; text-decoration: none;
    }
    .btn-action:hover { background: var(--bg); border-color: #cbd5e1; }
    .btn-action.danger:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

    /* DRAWER & FORM MODAL */
    .ov { position: fixed; inset: 0; background: #0f172a80; z-index: 60; }
    .dr { position: fixed; top: 0; right: 0; bottom: 0; width: min(470px, 100%); background: var(--cd); z-index: 70; display: flex; flex-direction: column; transform: translateX(100%); transition: .25s ease; box-shadow: -10px 0 30px rgba(0,0,0,.15); }
    .dr.on { transform: none; }
    .dh { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--ln); font-size: 18px; font-weight: 700; }
    .db { flex: 1; overflow: auto; padding: 24px; }
    .df { display: flex; gap: 10px; padding: 16px 24px; border-top: 1px solid var(--ln); background: var(--bg); }
    label { display: block; margin-bottom: 16px; font-weight: 600; font-size: 14px; }
    label small { font-weight: 400; color: var(--mu); margin-left: 6px; display: block; margin-top: 2px; }
    input:not([type=checkbox]), select, textarea { display: block; width: 100%; margin-top: 6px; padding: 10px 14px; border: 1px solid var(--ln); border-radius: 8px; background: var(--bg); color: var(--tx); font: inherit; }
    .drop { display: grid; place-items: center; gap: 4px; min-height: 130px; border: 2px dashed var(--ln); border-radius: 12px; background: var(--bg); color: var(--mu); cursor: pointer; margin-top: 6px; overflow: hidden; }
    .drop img { max-width: 100%; max-height: 160px; object-fit: contain; }
</style>
@endsection

@section('content')
<div class="am">
    <div class="breadcrumb-top">Kategori</div>

    <div class="ph">
        <div>
            <h1>Produk &amp; Kategori</h1>
            <p>Kartu kategori di Home. Klik kotak foto untuk mengunggah gambar, klik "Buka" untuk mengelola model.</p>
        </div>
        <button class="b" onclick="openCategoryDrawer()">+ Tambah kategori</button>
    </div>

    @if(session('success'))
        <div style="padding: 12px 16px; background: #dcfce7; color: #166534; border-radius: 10px; margin-bottom: 18px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <div class="cat-list">
        @forelse($categories as $category)
            <div class="cat-card-row">
                <div class="cat-thumb">
                    @if($category->image_path)
                        <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}">
                    @else
                        🚌
                    @endif
                </div>
                <div class="cat-details">
                    <b>{{ $category->name }}</b>
                    <small>{{ $category->models_count ?? 0 }} model</small>
                </div>
                <div class="cat-actions">
                    <a href="#" class="btn-action">Buka &rsaquo;</a>
                    <label class="sw">
                        <input type="checkbox" {{ $category->is_active ? 'checked' : '' }}>
                        <i></i>
                    </label>
                    <button class="btn-action">✏️ Ubah</button>
                    <button class="btn-action danger">🗑️ Hapus</button>
                </div>
            </div>
        @empty
            <div style="background:var(--cd); border:1px solid var(--ln); border-radius:16px; padding:48px; text-align:center;">
                <p class="mu" style="margin-bottom:16px;">Belum ada kategori produk di database.</p>
                <button class="b" onclick="openCategoryDrawer()">+ Tambah kategori</button>
            </div>
        @endforelse
    </div>
</div>

<!-- OVERLAY & DRAWER FORM TAMBAH/UBAH KATEGORI -->
<div class="ov" id="ov" hidden onclick="closeCategoryDrawer()"></div>
<div class="dr" id="dr">
    <div class="dh">
        <span id="drTitle">Tambah Kategori</span>
        <button class="b o s" onclick="closeCategoryDrawer()">✕</button>
    </div>
    <form id="categoryForm" method="POST" action="#" enctype="multipart/form-data">
        @csrf
        <div id="methodField"></div>
        <div class="db">
            <label>
                Nama Kategori *
                <input type="text" name="name" id="catName" placeholder="Contoh: Medium Bus" required>
            </label>
            <label>
                Teks Singkat (Card Home)
                <small>Contoh: MED atau BIG</small>
                <input type="text" name="short_text" id="catShort" placeholder="MED">
            </label>
            <label>
                Warna Latar Belakang Kartu
                <small>Kode Hex warna (contoh: #0c4a7a)</small>
                <input type="text" name="color_bg" id="catColor" value="#0c4a7a">
            </label>
            <label>
                Foto Poster Kategori
                <small>Foto bus landscape untuk tampilan di Home</small>
                <div class="drop" onclick="document.getElementById('catImageInput').click()">
                    <span id="imgPreviewText">Klik untuk unggah foto</span>
                    <img id="imgPreview" src="" hidden alt="Preview">
                </div>
                <input type="file" name="image" id="catImageInput" accept="image/*" hidden onchange="previewCategoryImg(this)">
            </label>
        </div>
        <div class="df">
            <button type="submit" class="b" style="flex:1;">Simpan Kategori</button>
            <button type="button" class="b o" onclick="closeCategoryDrawer()">Batal</button>
        </div>
    </form>
</div>

<script>
    function openCategoryDrawer() {
        document.getElementById('drTitle').textContent = 'Tambah Kategori';
        document.getElementById('categoryForm').action = "#"; // Ganti dengan route store kategori nanti
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('categoryForm').reset();
        document.getElementById('imgPreview').hidden = true;
        document.getElementById('imgPreviewText').hidden = false;
        document.getElementById('dr').classList.add('on');
        document.getElementById('ov').hidden = false;
    }

    function closeCategoryDrawer() {
        document.getElementById('dr').classList.remove('on');
        document.getElementById('ov').hidden = true;
    }

    function previewCategoryImg(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imgPreview').src = e.target.result;
                document.getElementById('imgPreview').hidden = false;
                document.getElementById('imgPreviewText').hidden = true;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
