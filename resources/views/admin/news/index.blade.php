@extends('layouts.admin')

@section('title', 'Berita & Event')
@section('page_icon', 'news')
@section('page_title', 'Berita & Event')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-news.css') }}?v={{ @filemtime(public_path('css/admin-news.css')) }}">
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert-error">
            <b>Berita belum tersimpan:</b>
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="ph">
        <div>
            <h1>Berita &amp; Event</h1>
            <p class="mu">Slider berita di Home. Thumbnail tampil di slider dan di daftar ini.</p>
        </div>
        <button type="button" class="b" id="newsAdd"><svg class="i"><use href="#i-plus"/></svg> Tambah berita</button>
    </div>

    @forelse ($news as $n)
        <div class="row">
            {{-- kotak foto: klik untuk ganti thumbnail langsung --}}
            <form method="POST" action="{{ route('admin.news.thumbnail', $n) }}" enctype="multipart/form-data" style="margin:0">
                @csrf @method('PATCH')
                <label class="ri up" title="Klik untuk ganti thumbnail">
                    @if ($n->thumbnail_url)
                        <img src="{{ $n->thumbnail_url }}" alt="">
                    @else
                        <svg class="i"><use href="#i-news"/></svg>
                    @endif
                    <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" hidden onchange="this.form.submit()">
                    <em class="cm"><svg class="i"><use href="#i-up"/></svg></em>
                </label>
            </form>

            <div class="rt">
                <b>{{ $n->tr('title', 'id') }}</b>
                <small>{{ $n->category }} · {{ $n->published_at->format('d M Y') }}</small>
            </div>

            <label class="sw" title="Tayang di website">
                <input type="checkbox" class="js-toggle" data-url="{{ route('admin.news.toggle', $n) }}" {{ $n->is_active ? 'checked' : '' }}><i></i>
            </label>

            <button type="button" class="b o s js-edit" data-id="{{ $n->id }}"><svg class="i"><use href="#i-edit"/></svg> Ubah</button>

            <form method="POST" action="{{ route('admin.news.destroy', $n) }}" class="js-del" style="margin:0">
                @csrf @method('DELETE')
                <button type="button" class="b o s js-del-btn"><svg class="i"><use href="#i-trash"/></svg> Hapus</button>
            </form>
        </div>
    @empty
        <div class="emp">Belum ada berita. Klik “Tambah berita” untuk membuat.</div>
    @endforelse

    @if ($news->hasPages())
        <div class="pager">
            @if ($news->onFirstPage())
                <span class="b o s dis"><svg class="i"><use href="#i-chl"/></svg> Sebelumnya</span>
            @else
                <a class="b o s" href="{{ $news->previousPageUrl() }}"><svg class="i"><use href="#i-chl"/></svg> Sebelumnya</a>
            @endif
            <span class="mu">Halaman {{ $news->currentPage() }} dari {{ $news->lastPage() }}</span>
            @if ($news->hasMorePages())
                <a class="b o s" href="{{ $news->nextPageUrl() }}">Berikutnya <svg class="i"><use href="#i-chr"/></svg></a>
            @else
                <span class="b o s dis">Berikutnya <svg class="i"><use href="#i-chr"/></svg></span>
            @endif
        </div>
    @endif
@endsection

@push('modals')
<div class="ov" id="nOv" hidden></div>

{{-- FORM DI SISI KANAN: tambah / ubah berita --}}
<div class="dr" id="nDr">
    <form id="nForm" method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <input type="hidden" name="_method" value="PUT" id="nMethod" disabled>

        <div class="dh">
            <b id="nTitle">Tambah berita</b>
            <button type="button" class="b o s" data-close aria-label="Tutup"><svg class="i"><use href="#i-x"/></svg></button>
        </div>

        <div class="db">
            <div class="seg" role="tablist" aria-label="Bahasa isi">
                <button type="button" data-lang="id" class="on">ID</button>
                <button type="button" data-lang="en">EN</button>
                <button type="button" data-lang="zh">中文</button>
            </div>
            <p class="mu" style="margin:0 0 14px;font-size:13px">Terjemahan diisi manual. Jika kosong, website memakai bahasa ID.</p>

            {{-- thumbnail --}}
            <label>Thumbnail <small>tampil di slider berita dan daftar</small>
                <small class="mu" style="display:block">
                    Boleh upload foto ukuran berapa saja. Di website akan otomatis dipotong tengah dan
                    disesuaikan menjadi <b>880 × 400 px</b> (rasio 11:5). Untuk hasil terbaik, upload foto
                    dengan ukuran/rasio itu. JPG/PNG/WebP, maks. 8 MB.
                </small>
                <span class="drop" id="nThumbDrop">
                    <img id="nThumbImg" alt="" hidden>
                    <span id="nThumbHint"><svg class="i"><use href="#i-up"/></svg><b>Klik untuk unggah foto</b></span>
                </span>
                <input type="file" name="thumbnail" id="nThumb" accept="image/jpeg,image/png,image/webp" hidden>
            </label>
            <label class="ck" id="nRmWrap" hidden><input type="checkbox" name="remove_thumbnail" value="1"> Hapus thumbnail</label>

            {{-- judul per bahasa --}}
            <label data-lg="id">Judul (ID) <em>*</em><input name="title[id]" maxlength="200" autocomplete="off"></label>
            <label data-lg="en" hidden>Judul (EN)<input name="title[en]" maxlength="200" autocomplete="off"></label>
            <label data-lg="zh" hidden>Judul (中文)<input name="title[zh]" maxlength="200" autocomplete="off"></label>

            <div class="g2">
                <label>Kategori
                    <select name="category">
                        @foreach (\App\Models\News::CATEGORIES as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Tanggal <em>*</em><input type="date" name="published_at"></label>
            </div>

            <label class="ck"><input type="checkbox" name="is_active" value="1" checked> Tayangkan di website</label>

            {{-- isi artikel --}}
            <div class="bk">
                <b>Isi artikel</b>
                <small class="mu"> Susun paragraf dan gambar sesuai urutan tampil. Gambar bisa diletakkan di tengah paragraf.</small>
                <div id="nBlocks"></div>
                <div class="fa">
                    <button type="button" class="b o s" id="nAddP"><svg class="i"><use href="#i-plus"/></svg> Paragraf</button>
                    <button type="button" class="b o s" id="nAddI"><svg class="i"><use href="#i-plus"/></svg> Gambar</button>
                </div>
            </div>
        </div>

        <div class="df">
            <button type="submit" class="b" id="nSave">Simpan</button>
            <button type="button" class="b o" data-close>Batal</button>
        </div>
    </form>
</div>
<div id="tst" hidden></div>
@endpush

@push('scripts')
<script>
    window.NEWS_CFG = {
        store:   @json(route('admin.news.store')),
        today:   @json(now()->format('Y-m-d')),
        openAdd: @json(request()->boolean('tambah')),
        data:    @json((object) $editData)
    };
</script>
<script src="{{ asset('js/admin-news.js') }}?v={{ @filemtime(public_path('js/admin-news.js')) }}"></script>
@endpush
