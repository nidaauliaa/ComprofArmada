@extends('layouts.admin')

@section('title', 'Banner Home')
@section('page_icon', 'image')
@section('page_title', 'Banner')

@section('content')
    <div class="ph">
        <div>
            <h1>Banner Home</h1>
            <p class="mu">Slide di bagian atas halaman Home.</p>
        </div>
        <button type="button" class="b" onclick="openDrawer()"><svg class="i"><use href="#i-plus"/></svg> Tambah banner</button>
    </div>

    @foreach($banners as $banner)
        <div class="row">
            <div class="ri">
                @if($banner->image_path)
                    <img src="{{ asset('storage/' . $banner->image_path) }}" alt="">
                @else
                    <svg class="i" style="width:22px;height:22px"><use href="#i-image"/></svg>
                @endif
            </div>
            <div class="rt">
                <b>{{ $banner->title }}</b>
                <small>Tombol: {{ $banner->button_text ?? 'Lihat Kategori Bus ↓' }}</small>
            </div>
            <label class="sw">
                <input type="checkbox" onchange="toggleBanner({{ $banner->id }})" {{ $banner->is_active ? 'checked' : '' }}>
                <i></i>
            </label>
            <button class="b o s" onclick="editBanner({{ json_encode($banner) }})"><svg class="i"><use href="#i-edit"/></svg> Ubah</button>
            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Hapus banner ini?')" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="b o s"><svg class="i"><use href="#i-trash"/></svg> Hapus</button>
            </form>
        </div>
    @endforeach
@endsection

@push('modals')
    <!-- OVERLAY & DRAWER FORM -->
    <div class="ov" id="ov" hidden onclick="closeDrawer()"></div>
    <div class="dr" id="dr">
        <div class="dh">
            <span id="drTitle">Ubah banner</span>
            <button class="b o s" onclick="closeDrawer()"><svg class="i"><use href="#i-x"/></svg></button>
        </div>
        <form id="bannerForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodField"></div>
            <div class="db">
                <div class="seg">
                    <button type="button" class="on" onclick="switchLang('id', this)">ID</button>
                    <button type="button" onclick="switchLang('en', this)">EN</button>
                    <button type="button" onclick="switchLang('zh', this)">中文</button>
                </div>
                <p class="mu" style="font-size: 13px; margin-bottom: 14px;">Terjemahan diisi manual. Jika kosong, website memakai bahasa ID.</p>

                <label>
                    Foto latar <small>Foto landscape. Kosongkan untuk latar gradasi</small>
                    <div class="drop" onclick="document.getElementById('imageInput').click()">
                        <span id="imgPreviewText">Klik untuk unggah foto</span>
                        <img id="imgPreview" src="" hidden>
                    </div>
                    <input type="file" name="image" id="imageInput" accept="image/*" hidden onchange="previewImage(this)">
                </label>

                <!-- TAB BHS INDONESIA -->
                <div class="lang-group" id="lang-id">
                    <label>Label kecil<input type="text" name="tag" id="tag"></label>
                    <label>Judul *<input type="text" name="title" id="title" required></label>
                    <label>Teks gradasi (baris kedua)<input type="text" name="headline" id="headline"></label>
                    <label>Deskripsi<textarea name="description" id="description" rows="3"></textarea></label>
                    <label>Teks tombol<input type="text" name="button_text" id="button_text"></label>
                </div>

                <!-- TAB BHS INGGRIS -->
                <div class="lang-group" id="lang-en" style="display:none;">
                    <label>Label kecil (EN)<input type="text" name="tag_en" id="tag_en"></label>
                    <label>Judul (EN)<input type="text" name="title_en" id="title_en"></label>
                    <label>Teks gradasi (EN)<input type="text" name="headline_en" id="headline_en"></label>
                    <label>Deskripsi (EN)<textarea name="description_en" id="description_en" rows="3"></textarea></label>
                    <label>Teks tombol (EN)<input type="text" name="button_text_en" id="button_text_en"></label>
                </div>

                <!-- TAB BHS MANDARIN -->
                <div class="lang-group" id="lang-zh" style="display:none;">
                    <label>Label kecil (ZH)<input type="text" name="tag_zh" id="tag_zh"></label>
                    <label>Judul (ZH)<input type="text" name="title_zh" id="title_zh"></label>
                    <label>Teks gradasi (ZH)<input type="text" name="headline_zh" id="headline_zh"></label>
                    <label>Deskripsi (ZH)<textarea name="description_zh" id="description_zh" rows="3"></textarea></label>
                    <label>Teks tombol (ZH)<input type="text" name="button_text_zh" id="button_text_zh"></label>
                </div>

                <label>
                    Tombol mengarah ke
                    <select name="button_link" id="button_link">
                        <option value="#produk">Kategori Produk</option>
                        <option value="#unit">Bisnis Unit</option>
                        <option value="#berita">Berita</option>
                        <option value="#plant">Lokasi Plant</option>
                        <option value="/kontak">Halaman Kontak</option>
                    </select>
                </label>
            </div>
            <div class="df">
                <button type="submit" class="b">Simpan</button>
                <button type="button" class="b o" onclick="closeDrawer()">Batal</button>
            </div>
        </form>
    </div>
@endpush

@push('scripts')
<script>
        function switchLang(lang, btn) {
            document.querySelectorAll('.seg button').forEach(b => b.classList.remove('on'));
            btn.classList.add('on');
            document.querySelectorAll('.lang-group').forEach(g => g.style.display = 'none');
            document.getElementById('lang-' + lang).style.display = 'block';
        }

        function openDrawer() {
            document.getElementById('drTitle').textContent = 'Tambah banner';
            document.getElementById('bannerForm').action = "{{ route('admin.banners.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('bannerForm').reset();
            document.getElementById('imgPreview').hidden = true;
            document.getElementById('imgPreviewText').hidden = false;
            document.getElementById('dr').classList.add('on');
            document.getElementById('ov').hidden = false;
        }

        function editBanner(b) {
            document.getElementById('drTitle').textContent = 'Ubah banner';
            document.getElementById('bannerForm').action = "/admin/banners/" + b.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            ['tag','title','headline','description','button_text','tag_en','title_en','headline_en','description_en','button_text_en','tag_zh','title_zh','headline_zh','description_zh','button_text_zh','button_link'].forEach(k => {
                const el = document.getElementById(k);
                if(el) el.value = b[k] || '';
            });

            if (b.image_path) {
                document.getElementById('imgPreview').src = "/storage/" + b.image_path;
                document.getElementById('imgPreview').hidden = false;
                document.getElementById('imgPreviewText').hidden = true;
            } else {
                document.getElementById('imgPreview').hidden = true;
                document.getElementById('imgPreviewText').hidden = false;
            }

            document.getElementById('dr').classList.add('on');
            document.getElementById('ov').hidden = false;
        }

        function closeDrawer() {
            document.getElementById('dr').classList.remove('on');
            document.getElementById('ov').hidden = true;
        }

        function previewImage(input) {
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

        function toggleBanner(id) {
            fetch("/admin/banners/" + id + "/toggle", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            });
        }
    </script>
@endpush
