@extends('layouts.admin')

@section('title', 'Produk & Kategori')
@section('page_icon', 'bus')
@section('page_title', 'Produk')

@push('styles')
{{-- memakai gaya yang sama dengan halaman Berita/Lokasi (kotak error, tampilan kosong) --}}
<link rel="stylesheet" href="{{ asset('css/admin-news.css') }}?v={{ @filemtime(public_path('css/admin-news.css')) }}">
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert-error">
            <b>Kategori belum tersimpan:</b>
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="ph">
        <div>
            <h1>Produk &amp; Kategori</h1>
            <p class="mu">Kartu kategori di Home. Klik “Buka” untuk mengelola produk di dalam kategori.</p>
        </div>
        <button type="button" class="b js-add"><svg class="i"><use href="#i-plus"/></svg> Tambah kategori</button>
    </div>

    @forelse ($categories as $c)
        <div class="row">
            <div class="ri">
                @if ($c->image_path)
                    <img src="{{ asset('storage/' . $c->image_path) }}" alt="">
                @else
                    <svg class="i" style="width:22px;height:22px"><use href="#i-bus"/></svg>
                @endif
            </div>

            <div class="rt">
                <b>{{ $c->name }}</b>
                <small>{{ $c->models_count }} model</small>
            </div>

            <a class="b o s" href="{{ route('admin.products.models.index', $c) }}">Buka <svg class="i"><use href="#i-chr"/></svg></a>

            <label class="sw" title="Tayang di website">
                <input type="checkbox" class="js-toggle" data-url="{{ route('admin.products.categories.toggle', $c) }}" {{ $c->is_active ? 'checked' : '' }}>
                <i></i>
            </label>

            <button type="button" class="b o s js-edit" data-edit="{{ json_encode($editData[$c->id]) }}"><svg class="i"><use href="#i-edit"/></svg> Ubah</button>

            <form method="POST" action="{{ route('admin.products.categories.destroy', $c) }}"
                  data-msg="Hapus kategori {{ $c->name }}? Semua model di dalamnya ikut terhapus."
                  onsubmit="return confirm(this.dataset.msg)">
                @csrf
                @method('DELETE')
                <button type="submit" class="b o s"><svg class="i"><use href="#i-trash"/></svg> Hapus</button>
            </form>
        </div>
    @empty
        <div class="emp">
            Belum ada kategori. Klik “Tambah kategori” untuk membuat.
        </div>
    @endforelse
@endsection

@push('modals')
    <div class="ov" id="cOv" hidden></div>
    <div class="dr" id="cDr">
        <div class="dh">
            <span id="cTitle">Tambah kategori</span>
            <button type="button" class="b o s" id="cClose" aria-label="Tutup"><svg class="i"><use href="#i-x"/></svg></button>
        </div>
        <form id="cForm" method="POST" action="{{ route('admin.products.categories.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT" id="cMethod" disabled>
            <div class="db">
                <label>
                    Nama kategori <small>wajib diisi</small>
                    <input type="text" name="name" id="cName" placeholder="Contoh: Medium Bus" required>
                </label>
                <label>
                    Teks singkat <small>untuk kartu di Home, contoh: MED</small>
                    <input type="text" name="short_text" id="cShort" placeholder="MED">
                </label>
                <label>
                    Warna latar kartu
                    <input type="color" name="color_bg" id="cColor" value="#12274d" style="height:44px;padding:4px">
                </label>
                <label>
                    Foto poster kategori <small>foto bus landscape untuk tampilan di Home, maks. 50 MB</small>
                    <div class="drop" id="cDrop">
                        <span id="cImgText">Klik untuk unggah foto</span>
                        <img id="cImg" src="" alt="" hidden>
                    </div>
                    <input type="file" name="image" id="cFile" accept="image/*" hidden>
                </label>
            </div>
            <div class="df">
                <button type="submit" class="b">Simpan</button>
                <button type="button" class="b o" id="cCancel">Batal</button>
            </div>
        </form>
    </div>
@endpush

@push('scripts')
<script>
(function () {
    var $ = function (id) { return document.getElementById(id); };
    var STORE = "{{ route('admin.products.categories.store') }}";
    var CSRF = document.querySelector('meta[name="csrf-token"]').content;
    var form = $('cForm');

    function openDr(title) {
        $('cTitle').textContent = title;
        $('cDr').classList.add('on');
        $('cOv').hidden = false;
    }
    function closeDr() {
        $('cDr').classList.remove('on');
        $('cOv').hidden = true;
    }
    function setImg(src) {
        $('cImg').src = src || '';
        $('cImg').hidden = !src;
        $('cImgText').hidden = !!src;
    }

    document.querySelectorAll('.js-add').forEach(function (btn) {
        btn.addEventListener('click', function () {
            form.reset();
            form.action = STORE;
            $('cMethod').disabled = true;
            $('cColor').value = '#12274d';
            setImg(null);
            openDr('Tambah kategori');
        });
    });

    document.querySelectorAll('.js-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var d = JSON.parse(btn.dataset.edit);
            form.reset();
            form.action = d.update_url;
            $('cMethod').disabled = false;
            $('cName').value = d.name || '';
            $('cShort').value = d.short_text || '';
            $('cColor').value = d.color_bg || '#12274d';
            setImg(d.image);
            openDr('Ubah kategori');
        });
    });

    $('cDrop').addEventListener('click', function () { $('cFile').click(); });
    $('cFile').addEventListener('change', function () {
        var f = this.files && this.files[0];
        if (!f) return;
        var r = new FileReader();
        r.onload = function (e) { setImg(e.target.result); };
        r.readAsDataURL(f);
    });

    $('cClose').addEventListener('click', closeDr);
    $('cCancel').addEventListener('click', closeDr);
    $('cOv').addEventListener('click', closeDr);

    // saklar tayang / sembunyikan tanpa reload
    document.querySelectorAll('.js-toggle').forEach(function (cb) {
        cb.addEventListener('change', function () {
            fetch(cb.dataset.url, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            }).then(function (r) { if (!r.ok) throw new Error(); })
              .catch(function () { cb.checked = !cb.checked; alert('Gagal mengubah status. Coba lagi.'); });
        });
    });
})();
</script>
@endpush
