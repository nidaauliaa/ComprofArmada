@extends('layouts.admin')

@section('title', 'Produk ' . $category->name)
@section('page_icon', 'bus')
@section('page_title', 'Produk')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-news.css') }}?v={{ @filemtime(public_path('css/admin-news.css')) }}">
<style>
    .crumb{display:inline-flex;align-items:center;gap:4px;color:var(--mu);font-weight:600;font-size:13px;margin-bottom:10px}
    .crumb:hover{color:var(--pr)}
    .g3{display:grid;grid-template-columns:repeat(3,1fr);gap:0 10px}
    .gl{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin:6px 0 10px}
    .gl .gi{position:relative;aspect-ratio:4/3;border-radius:8px;overflow:hidden;border:1px solid var(--ln);background:var(--bg)}
    .gl .gi .gi-open{position:absolute;inset:0;width:100%;height:100%;padding:0;border:0;border-radius:0;background:transparent;cursor:zoom-in}
    .gl .gi .gi-open img{width:100%;height:100%;object-fit:cover;display:block}
    .gl .gi .gi-open:focus-visible{outline:2px solid var(--pr);outline-offset:-3px}
    .gl .gi .gi-remove{position:absolute;top:4px;right:4px;z-index:2;width:22px;height:22px;padding:0;border:0;border-radius:50%;background:#0f172acc;color:#fff;cursor:pointer;font-size:12px;line-height:22px}
    .gl .gi.new{outline:2px solid var(--pr)}
    .gh{font-size:13px;color:var(--mu);margin:0 0 4px}
    .photo-preview{width:min(960px,calc(100vw - 32px));max-height:calc(100dvh - 32px);margin:auto;padding:0;border:0;background:transparent;color:#fff;overflow:visible}
    .photo-preview::backdrop{background:rgba(15,23,42,.72);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}
    .photo-preview-shell{position:relative;display:grid;gap:8px;padding:12px;border:1px solid rgba(255,255,255,.22);border-radius:16px;background:rgba(15,23,42,.64);box-shadow:0 24px 70px rgba(0,0,0,.35);-webkit-backdrop-filter:blur(14px);backdrop-filter:blur(14px)}
    .photo-preview-shell img{display:block;width:100%;max-height:calc(100dvh - 130px);object-fit:contain;border-radius:10px;background:#0f172a}
    .photo-preview-shell p{margin:0;padding:0 4px 2px;color:rgba(255,255,255,.78);font-size:12px;line-height:1.5}
    .photo-preview-close{position:absolute;top:18px;right:18px;z-index:1;display:grid;place-items:center;width:36px;height:36px;padding:0;border:1px solid rgba(255,255,255,.42);border-radius:9px;background:rgba(255,255,255,.18);color:#fff;cursor:pointer;-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}
    .photo-preview-close:hover{background:rgba(255,255,255,.3)}
    .photo-preview-close:focus-visible{outline:2px solid #fff;outline-offset:2px}
    @media(max-width:640px){.photo-preview-shell{padding:8px}.photo-preview-close{top:14px;right:14px}}
</style>
@endpush

@section('content')
    <a class="crumb" href="{{ route('admin.products.index') }}"><svg class="i"><use href="#i-chl"/></svg> Semua kategori</a>

    @if ($errors->any())
        <div class="alert-error">
            <b>Produk belum tersimpan:</b>
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="ph">
        <div>
            <h1>{{ $category->name }}</h1>
            <p class="mu">Produk dari kategori ini akan aktif tampil di halaman Produk website.</p>
        </div>
        <div class="ph-actions">
            <button type="button" class="b js-add"><svg class="i"><use href="#i-plus"/></svg> Tambah produk</button>
            <x-admin-view-page :href="route('product.index', ['kategori' => $category->slug])" :page="$category->name" />
        </div>
    </div>

    <x-admin-locale-switch storage-key="na-products-locale" help="Pilihan ini berlaku untuk terjemahan subjudul dan deskripsi produk." />

    @forelse ($models as $m)
        <div class="row">
            <div class="ri">
                @if ($m->image_path)
                    <img src="{{ asset('storage/' . $m->image_path) }}" alt="">
                @else
                    <svg class="i" style="width:22px;height:22px"><use href="#i-image"/></svg>
                @endif
            </div>

            <div class="rt">
                <b>{{ $m->name }}</b>
                <small>{{ $m->subtitle ?: 'Tanpa subjudul' }} · {{ $m->galleries->count() }} foto galeri</small>
            </div>

            <label class="sw" title="Tayang di website">
                <input type="checkbox" class="js-toggle" data-url="{{ route('admin.products.models.toggle', $m) }}" {{ $m->is_active ? 'checked' : '' }}>
                <i></i>
            </label>

            <button type="button" class="b o s js-edit" data-edit="{{ json_encode($editData[$m->id]) }}"><svg class="i"><use href="#i-edit"/></svg> Ubah</button>

            <form method="POST" action="{{ route('admin.products.models.destroy', $m) }}"
                  data-msg="Hapus produk {{ $m->name }} beserta foto galerinya?"
                  onsubmit="return confirm(this.dataset.msg)">
                @csrf
                @method('DELETE')
                <button type="submit" class="b o s"><svg class="i"><use href="#i-trash"/></svg> Hapus</button>
            </form>
        </div>
    @empty
        <div class="emp">
            Belum ada produk di kategori {{ $category->name }}. Klik “Tambah produk” untuk membuat.
        </div>
    @endforelse
@endsection

@push('modals')
    <div class="ov" id="mOv" hidden></div>
    <div class="dr" id="mDr">
        <div class="dh">
            <span id="mTitle">Tambah produk</span>
            <button type="button" class="b o s" id="mClose" aria-label="Tutup"><svg class="i"><use href="#i-x"/></svg></button>
        </div>
        <form id="mForm" method="POST" action="{{ route('admin.products.models.store', $category) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT" id="mMethod" disabled>
            <div class="db">
                <div data-admin-locale-drawer-host></div>
                <label>
                    Nama produk <small>wajib diisi</small>
                    <input type="text" name="name" id="mName" placeholder="Contoh: Tourista 25" required>
                </label>
                <label>
                    Subjudul <small>contoh: Medium Bus · Executive</small>
                    <input type="text" name="subtitle" id="mSub">
                </label>
                <x-lang-tabs name="subtitle" mode="suffix" id-base="mSub" label="Terjemahan subjudul" :only="['en','zh']" />
                <label>
                    Merek
                    <input type="text" name="brand" id="mBrand" placeholder="NEW ARMADA">
                </label>

                <label>
                    Foto utama <small>foto bus landscape (maks. 50 MB)</small>
                    <div class="drop" id="mDrop">
                        <span id="mImgText">Klik untuk unggah foto</span>
                        <img id="mImg" src="" alt="" hidden>
                    </div>
                    <input type="file" name="image" id="mFile" accept="image/*" hidden>
                </label>

                <div class="g3">
                    <label>Tinggi<input type="text" name="height" id="mH" placeholder="3.250 mm"></label>
                    <label>Lebar<input type="text" name="width" id="mW" placeholder="2.400 mm"></label>
                    <label>Panjang<input type="text" name="length" id="mL" placeholder="9.000 mm"></label>
                </div>

                <label>
                    Deskripsi
                    <textarea name="description" id="mDesc" rows="4"></textarea>
                </label>
                <x-lang-tabs name="description" mode="suffix" id-base="mDesc" type="textarea" :rows="4" label="Terjemahan deskripsi" :only="['en','zh']" help="Kosong = website memakai Bahasa Indonesia." />

                <div>
                    <b>Galeri foto</b>
                    <p class="gh" id="mGalHint">Pilih beberapa foto sekaligus atau tambah bertahap. Klik (✕) untuk menghapus.</p>
                    <div class="gl" id="mGalOld"></div>
                    <button type="button" class="b o s" id="mGalBtn"><svg class="i"><use href="#i-plus"/></svg> Tambah foto galeri</button>
                    <input type="file" name="galleries[]" id="mGalFile" accept="image/*" multiple hidden>
                    <div class="gl" id="mGalNew"></div>
                </div>
            </div>
            <div class="df">
                <button type="submit" class="b">Simpan</button>
                <button type="button" class="b o" id="mCancel">Batal</button>
            </div>
        </form>
    </div>
    <dialog class="photo-preview" id="mGalPreview" aria-labelledby="mGalPreviewLabel">
        <div class="photo-preview-shell">
            <button type="button" class="photo-preview-close" id="mGalPreviewClose" aria-label="Tutup pratinjau foto">
                <svg class="i"><use href="#i-x"/></svg>
            </button>
            <img id="mGalPreviewImg" src="" alt="">
            <p id="mGalPreviewLabel"></p>
        </div>
    </dialog>
@endpush

@push('scripts')
<script>
(function () {
    var $ = function (id) { return document.getElementById(id); };
    var STORE = "{{ route('admin.products.models.store', $category) }}";
    var GAL_DEL = "{{ url('admin/products/galleries') }}/";
    var CSRF = document.querySelector('meta[name="csrf-token"]').content;
    var form = $('mForm');
    var galleryDraft = new DataTransfer();
    var galleryLimit = 20;

    function openDr(title) {
        $('mTitle').textContent = title;
        $('mDr').classList.add('on');
        $('mOv').hidden = false;
    }
    function closeDr() {
        $('mDr').classList.remove('on');
        $('mOv').hidden = true;
    }
    function setImg(src) {
        $('mImg').src = src || '';
        $('mImg').hidden = !src;
        $('mImgText').hidden = !!src;
    }
    function showGalleryPreview(src, label) {
        if (!src) return;

        var dialog = $('mGalPreview');
        $('mGalPreviewImg').src = src;
        $('mGalPreviewImg').alt = label || 'Pratinjau foto galeri';
        $('mGalPreviewLabel').textContent = label || 'Pratinjau foto galeri';

        if (typeof dialog.showModal === 'function') {
            if (!dialog.open) dialog.showModal();
        } else {
            dialog.setAttribute('open', '');
        }
    }
    function closeGalleryPreview() {
        var dialog = $('mGalPreview');
        if (typeof dialog.close === 'function') {
            dialog.close();
        } else {
            dialog.removeAttribute('open');
        }
    }
    function clearGal() {
        $('mGalOld').innerHTML = '';
        $('mGalNew').innerHTML = '';
        galleryDraft = new DataTransfer();
        syncGalleryInput();
    }

    function renderOldGallery(list) {
        var box = $('mGalOld');
        box.innerHTML = '';
        $('mGalHint').hidden = !list.length;
        list.forEach(function (g) {
            var d = document.createElement('div'); d.className = 'gi';
            var img = document.createElement('img'); img.src = g.url; img.alt = '';
            var open = document.createElement('button'); open.type = 'button'; open.className = 'gi-open'; open.title = 'Perbesar foto'; open.setAttribute('aria-label', 'Perbesar foto galeri');
            open.addEventListener('click', function () {
                showGalleryPreview(g.url, 'Foto galeri tersimpan');
            });
            var x = document.createElement('button'); x.type = 'button'; x.className = 'gi-remove'; x.textContent = '✕'; x.title = 'Hapus foto ini';
            x.addEventListener('click', function () {
                if (!confirm('Hapus foto ini dari galeri?')) return;
                fetch(GAL_DEL + g.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } })
                    .then(function (r) {
                        if (!r.ok) throw new Error();
                        d.remove();
                        window.AdminToast.show('Foto berhasil dihapus.', 'delete');
                    })
                    .catch(function () { alert('Gagal menghapus foto. Coba lagi.'); });
            });
            open.appendChild(img);
            d.appendChild(open); d.appendChild(x); box.appendChild(d);
        });
    }

    function syncGalleryInput() {
        $('mGalFile').files = galleryDraft.files;
        $('mGalBtn').disabled = galleryDraft.files.length >= galleryLimit;
    }

    function renderNewGallery() {
        var box = $('mGalNew');
        box.innerHTML = '';

        Array.prototype.forEach.call(galleryDraft.files, function (file, index) {
            var d = document.createElement('div'); d.className = 'gi new';
            var img = document.createElement('img'); img.alt = file.name;
            var previewSrc = '';
            var open = document.createElement('button'); open.type = 'button'; open.className = 'gi-open'; open.title = 'Perbesar foto'; open.setAttribute('aria-label', 'Perbesar ' + file.name);
            open.addEventListener('click', function () {
                showGalleryPreview(previewSrc, file.name);
            });
            var x = document.createElement('button'); x.type = 'button'; x.className = 'gi-remove'; x.textContent = '✕'; x.title = 'Batalkan foto ini';
            x.addEventListener('click', function () {
                galleryDraft.items.remove(index);
                syncGalleryInput();
                renderNewGallery();
                window.AdminToast.show('Foto berhasil dihapus dari draft.', 'delete');
            });
            var r = new FileReader();
            r.onload = function (e) {
                previewSrc = e.target.result;
                img.src = previewSrc;
            };
            r.readAsDataURL(file);
            open.appendChild(img);
            d.appendChild(open); d.appendChild(x); box.appendChild(d);
        });
    }

    document.querySelectorAll('.js-add').forEach(function (btn) {
        btn.addEventListener('click', function () {
            form.reset();
            form.action = STORE;
            $('mMethod').disabled = true;
            setImg(null);
            clearGal();
            form.dispatchEvent(new Event('input', { bubbles: true }));
            $('mGalHint').hidden = true;
            openDr('Tambah produk');
        });
    });

    document.querySelectorAll('.js-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var d = JSON.parse(btn.dataset.edit);
            form.reset();
            form.action = d.update_url;
            $('mMethod').disabled = false;
            $('mName').value = d.name || '';
            $('mSub').value = d.subtitle || '';
            $('mBrand').value = d.brand || '';
            $('mH').value = d.height || '';
            $('mW').value = d.width || '';
            $('mL').value = d.length || '';
            $('mDesc').value = d.description || '';
            $('mSubEn').value = d.subtitle_en || '';
            $('mSubZh').value = d.subtitle_zh || '';
            $('mDescEn').value = d.description_en || '';
            $('mDescZh').value = d.description_zh || '';
            form.dispatchEvent(new Event('input', { bubbles: true }));
            setImg(d.image);
            clearGal();
            renderOldGallery(d.galleries || []);
            openDr('Ubah produk');
        });
    });

    $('mDrop').addEventListener('click', function () { $('mFile').click(); });
    $('mFile').addEventListener('change', function () {
        var f = this.files && this.files[0];
        if (!f) return;
        var r = new FileReader();
        r.onload = function (e) { setImg(e.target.result); };
        r.readAsDataURL(f);
    });

    // galeri: pratinjau foto baru yang dipilih
    $('mGalBtn').addEventListener('click', function () { $('mGalFile').click(); });
    $('mGalFile').addEventListener('change', function () {
        var selected = Array.prototype.slice.call(this.files);
        var available = galleryLimit - galleryDraft.files.length;
        var accepted = selected.slice(0, available);

        accepted.forEach(function (file) { galleryDraft.items.add(file); });
        syncGalleryInput();
        renderNewGallery();

        if (accepted.length) {
            var uploadMessage = accepted.length === 1
                ? 'Foto berhasil diunggah ke draft.'
                : accepted.length + ' foto berhasil diunggah ke draft.';
            window.AdminToast.show(uploadMessage, 'upload');
        }

        if (selected.length > available) {
            alert('Maksimal ' + galleryLimit + ' foto galeri untuk sekali simpan.');
        }
    });

    $('mGalPreviewClose').addEventListener('click', closeGalleryPreview);
    $('mGalPreview').addEventListener('click', function (event) {
        if (event.target === this) closeGalleryPreview();
    });

    $('mClose').addEventListener('click', closeDr);
    $('mCancel').addEventListener('click', closeDr);
    $('mOv').addEventListener('click', closeDr);

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
