@extends('layouts.admin')

@section('title', 'Lokasi Plant')
@section('page_icon', 'pin')
@section('page_title', 'Lokasi Plant')

@push('styles')
{{-- memakai gaya yang sama dengan halaman Berita (kotak error, kolom 2, toast, dll) --}}
<link rel="stylesheet" href="{{ asset('css/admin-news.css') }}?v={{ @filemtime(public_path('css/admin-news.css')) }}">
<style>
    .phr{display:flex;gap:8px;margin-bottom:8px}
    .phr input{flex:1;margin:0}
    .sec-t{margin:18px 0 8px;font-weight:700}
</style>
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert-error">
            <b>Lokasi belum tersimpan:</b>
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="ph">
        <div>
            <h1>Lokasi Plant</h1>
            <p class="mu">Setiap lokasi menjadi pin di peta pada bagian Lokasi Plant di website. Lokasi bertanda HQ menjadi sumber info di halaman Kontak.</p>
        </div>
        <button type="button" class="b" id="plAdd"><svg class="i"><use href="#i-plus"/></svg> Tambah lokasi</button>
    </div>

    @forelse ($plants as $p)
        <div class="row">
            <span class="ri"><svg class="i"><use href="#i-pin"/></svg></span>

            <div class="rt">
                <b>{{ $p->name }} @if ($p->is_hq)<span class="bdg">HQ</span>@endif</b>
                <small>{{ $p->address }}</small>
            </div>

            <label class="sw" title="Tayang di website">
                <input type="checkbox" class="js-toggle" data-url="{{ route('admin.plants.toggle', $p) }}" {{ $p->is_active ? 'checked' : '' }}><i></i>
            </label>

            <button type="button" class="b o s js-edit" data-id="{{ $p->id }}"><svg class="i"><use href="#i-edit"/></svg> Ubah</button>

            <form method="POST" action="{{ route('admin.plants.destroy', $p) }}" class="js-del" style="margin:0">
                @csrf @method('DELETE')
                <button type="button" class="b o s js-del-btn"><svg class="i"><use href="#i-trash"/></svg> Hapus</button>
            </form>
        </div>
    @empty
        <div class="emp">Belum ada lokasi. Klik “Tambah lokasi” untuk membuat.</div>
    @endforelse
@endsection

@push('modals')
<div class="ov" id="pOv" hidden></div>

{{-- FORM DI SISI KANAN: tambah / ubah lokasi --}}
<div class="dr" id="pDr">
    <form id="pForm" method="POST" action="{{ route('admin.plants.store') }}" novalidate>
        @csrf
        <input type="hidden" name="_method" value="PUT" id="pMethod" disabled>

        <div class="dh">
            <b id="pTitle">Tambah lokasi</b>
            <button type="button" class="b o s" data-close aria-label="Tutup"><svg class="i"><use href="#i-x"/></svg></button>
        </div>

        <div class="db">
            <label>Nama plant <em>*</em><input name="name" maxlength="100" autocomplete="off" placeholder="Contoh: Magelang (HQ)"></label>
            <x-lang-tabs name="name" mode="suffix" id-base="pName" label="Terjemahan nama plant" :only="['en','zh']" />
            <label>Alamat <em>*</em><textarea name="address" rows="3" maxlength="500" placeholder="Alamat lengkap"></textarea></label>
            <x-lang-tabs name="address" mode="suffix" id-base="pAddr" type="textarea" :rows="2" label="Terjemahan alamat" :only="['en','zh']" help="Kosong = website memakai Bahasa Indonesia." />

            <label>Link Google Maps <small>untuk tombol “Buka di Google Maps” di website</small>
                <input type="url" name="maps_url" maxlength="500" autocomplete="off" placeholder="https://maps.app.goo.gl/...">
            </label>
            <p class="mu" style="margin:-6px 0 14px;font-size:13px">
                Cari lokasinya di Google Maps, klik <b>Bagikan</b>, lalu <b>Salin link</b> dan tempel di sini.
            </p>

            <div class="g2">
                <label>Latitude <em>*</em><input name="lat" id="pLat" inputmode="decimal" autocomplete="off" placeholder="-7.4950"></label>
                <label>Longitude <em>*</em><input name="lng" id="pLng" inputmode="decimal" autocomplete="off" placeholder="110.2200"></label>
            </div>
            <p class="mu" style="margin:-6px 0 14px;font-size:13px">
                Cara mengisi: buka Google Maps, klik kanan di titik lokasi, lalu klik angka koordinat paling atas untuk menyalinnya.
                Tempel (paste) ke kolom Latitude, kolom Longitude terisi otomatis.
            </p>

            {{-- KONTAK: dipakai di halaman /kontak (dari lokasi bertanda HQ) --}}
            <div class="sec-t">Kontak <small class="mu">opsional, tampil di halaman Kontak jika lokasi ini HQ</small></div>

            <div class="bk">
                <b>Nomor telepon</b> <small class="mu">bisa lebih dari satu</small>
                <div id="pPhones" style="margin-top:8px"></div>
                <button type="button" class="b o s" id="pPhoneAdd"><svg class="i"><use href="#i-plus"/></svg> Tambah nomor</button>
            </div>

            <label>Email <small>opsional, pesan dari form Kontak dikirim ke email ini</small>
                <input type="email" name="email" maxlength="150" autocomplete="off" placeholder="marketing@newarmada.co.id">
            </label>

            <label>Jam kerja <small>opsional</small>
                <input name="working_hours" maxlength="255" autocomplete="off" placeholder="Senin–Jumat, 08.00–16.00 WIB">
            </label>
            <x-lang-tabs name="working_hours" mode="suffix" id-base="pHours" label="Terjemahan jam kerja" :only="['en','zh']" help="Kosong = website memakai Bahasa Indonesia." />

            <label class="ck"><input type="checkbox" name="is_hq" value="1"> Kantor pusat (HQ) <small>info Kontak diambil dari lokasi ini; hanya satu lokasi yang bisa HQ</small></label>

            <label>Urutan tampil <small>angka kecil tampil lebih atas</small><input type="number" name="sort_order" min="0" max="9999"></label>

            <label class="ck"><input type="checkbox" name="is_active" value="1" checked> Tayangkan di website</label>
        </div>

        <div class="df">
            <button type="submit" class="b" id="pSave">Simpan</button>
            <button type="button" class="b o" data-close>Batal</button>
        </div>
    </form>
</div>
<div id="tst" hidden></div>
@endpush

@push('scripts')
<script>
    window.PLANT_CFG = {
        store: @json(route('admin.plants.store')),
        next:  @json($nextOrder),
        data:  @json((object) $editData)
    };
</script>
<script src="{{ asset('js/admin-plants.js') }}?v={{ @filemtime(public_path('js/admin-plants.js')) }}"></script>
@endpush
