@extends('layouts.admin')

@section('title', 'Pengaturan Situs')
@section('page_icon', 'gear')
@section('page_title', 'Pengaturan Situs')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-gustam.css') }}">
    <style>
        .settings-grid { display: grid; gap: 20px; }
    </style>
@endpush

@section('content')
    <div class="ph">
        <div>
            <h1>Pengaturan Situs</h1>
            <p class="mu">Data ini tampil di footer, halaman Kontak, video profil, hingga statistik media sosial.</p>
        </div>
        <x-admin-view-page :href="url('/')" page="Beranda" />
    </div>

    <x-admin-locale-switch storage-key="na-settings-locale" help="Pilihan ini berlaku untuk deskripsi SEO dalam tiga bahasa." />

    @if ($errors->any())
        <div class="alert-error">Ada isian yang belum benar: {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <h3 class="cardh">Identitas Situs</h3>

            <div style="margin-bottom:14px">
                <b>Logo New Armada</b>
                <small class="mu">
                    PNG/JPG/WEBP/SVG, maks. 2 MB.
                </small>

                @if (!empty($v['logo']))
                    <div style="margin:12px 0">
                        <img
                            class="logo-prev"
                            src="{{ asset('storage/'.$v['logo']) }}"
                            alt="Logo New Armada"
                        >
                    </div>

                    <label style="font-weight:500;margin:8px 0;display:block">
                        <input type="checkbox" name="remove_logo" value="1">
                        Hapus logo
                    </label>
                @endif

                <input type="file" name="logo"
                    accept="image/png,image/jpeg,image/webp,image/svg+xml">
            </div>

            <p class="mu" style="font-size:13px;margin:0">
                Tulisan NEW ARMADA tetap ditampilkan meskipun logo belum diunggah.
            </p>
        </div>

        {{-- CARD 1: VIDEO PROFIL / UNIT BISNIS --}}
        <div class="card">
            <h3 class="cardh">Video Profil Unit Bisnis</h3>
            <p class="mu" style="margin-bottom: 16px;">Digunakan untuk menampilkan video interaktif pada bagian Unit Bisnis di halaman utama.</p>

            <div style="margin-bottom:18px">
                <b>Upload video profil (MP4 / WebM / MOV)</b>
                <small class="mu" style="display:block; margin-bottom: 8px;">Maks. 300 MB. Jika diisi, video ini yang akan diputar dan link YouTube di bawah akan diabaikan</small>

                @if (!empty($v['profile_video']))
                    <video src="{{ asset('storage/'.$v['profile_video']) }}" controls preload="metadata"
                        style="display:block;max-width:360px;width:100%;margin:12px 0;border-radius:8px"></video>
                    <label style="font-weight:500;margin:8px 0;display:block">
                        <input type="checkbox" name="remove_profile_video" value="1"> Hapus video yang diupload
                    </label>
                @endif

                <input type="file" name="profile_video" accept="video/mp4,video/webm,video/quicktime">
            </div>

            <label class="ab-lbl" style="display:block;">
                <b>Atau gunakan link video YouTube</b>
                <input
                    type="url"
                    name="youtube_video_id"
                    value="{{ old('youtube_video_id', !empty($v['youtube_video_id']) ? 'https://youtu.be/'.$v['youtube_video_id'] : '') }}"
                    placeholder="https://youtu.be/xxxxxxxxxxx"
                    style="width:100%; box-sizing:border-box; border:1px solid #d1d5db; border-radius:8px; padding:9px 12px; margin-top:6px;"
                >
                <small class="mu" style="display:block; margin-top:4px;">Dipakai sebagai alternatif apabila tidak ada file video yang diunggah</small>
            </label>
        </div>

        {{-- CARD 2: STATISTIK AKUN INSTAGRAM --}}
        <div class="card">
            <h3 class="cardh">Statistik Akun Instagram</h3>
            <p class="mu" style="margin-bottom: 16px;">Masukkan angka atau teks ringkas untuk data statistik profil Instagram korporat.</p>

            <div class="grid2">
                <label>
                    Instagram: Jumlah Postingan
                    <input type="text" name="ig_posts"
                        value="{{ old('ig_posts', $v['ig_posts'] ?? '') }}"
                        placeholder="2.641">
                </label>

                <label>
                    Instagram: Pengikut (Followers)
                    <input type="text" name="ig_followers"
                        value="{{ old('ig_followers', $v['ig_followers'] ?? '') }}"
                        placeholder="109 rb">
                </label>

                <label style="grid-column: span 2;">
                    Instagram: Mengikuti (Following)
                    <input type="text" name="ig_following"
                        value="{{ old('ig_following', $v['ig_following'] ?? '') }}"
                        placeholder="525">
                </label>
            </div>
        </div>

        {{-- CARD 3: LINK SOSIAL MEDIA --}}
        <div class="card">
            <h3 class="cardh">Link Sosial Media</h3>
            <div class="grid2">
                <label>Instagram <input type="text" name="social_instagram" value="{{ old('social_instagram', $v['social_instagram'] ?? '') }}" placeholder="https://instagram.com/…"></label>
                <label>Facebook <input type="text" name="social_facebook" value="{{ old('social_facebook', $v['social_facebook'] ?? '') }}" placeholder="https://facebook.com/…"></label>
                <label>YouTube <input type="text" name="social_youtube" value="{{ old('social_youtube', $v['social_youtube'] ?? '') }}" placeholder="https://youtube.com/…"></label>
                <label>TikTok <input type="text" name="social_tiktok" value="{{ old('social_tiktok', $v['social_tiktok'] ?? '') }}" placeholder="https://tiktok.com/@…"></label>
                <label>LinkedIn <input type="text" name="social_linkedin" value="{{ old('social_linkedin', $v['social_linkedin'] ?? '') }}" placeholder="https://linkedin.com/company/…"></label>
            </div>
            <p class="mu" style="font-size:13px;margin-top:12px">Kosongkan yang tidak dipakai; link yang kosong tidak ditampilkan di footer.</p>
        </div>

        {{-- CARD 4: SEO --}}
        <div class="card">
            <h3 class="cardh">SEO (Hasil Pencarian Google)</h3>
            <x-lang-tabs name="meta_description" label="Deskripsi singkat situs (maks. 160 karakter)" type="textarea" :rows="3"
                         :values="$metaDescription" help="Terjemahan diisi manual. Jika kosong, dipakai Bahasa Indonesia." />
            <label style="display:block; margin-top:12px;">Kata kunci <small>pisahkan dengan koma</small>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $v['meta_keywords'] ?? '') }}">
            </label>
        </div>

        <div class="sp-save"><button type="submit" class="b">Simpan pengaturan</button></div>
    </form>
@endsection
