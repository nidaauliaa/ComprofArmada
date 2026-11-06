@extends('layouts.admin')

@section('title', 'Pengaturan Situs')
@section('page_icon', 'gear')
@section('page_title', 'Pengaturan Situs')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-extra.css') }}">
@endpush

@section('content')
    <div class="ph">
        <div>
            <h1>Pengaturan Situs</h1>
            <p class="mu">Data ini tampil di footer, halaman Kontak, dan hasil pencarian Google.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-error">Ada isian yang belum benar: {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <h3 class="cardh">Identitas</h3>
            <label>Nama perusahaan
                <input type="text" name="site_name" value="{{ old('site_name', $v['site_name'] ?? '') }}">
            </label>
            <div style="margin-bottom:14px">
                <b>Logo</b> <small class="mu">PNG/JPG/WEBP/SVG, maks. 2 MB. Kosong = tulisan NEW ARMADA.</small>
                @if (!empty($v['logo']))
                    <div><img class="logo-prev" src="{{ asset('storage/'.$v['logo']) }}" alt="Logo"></div>
                    <label style="font-weight:500;margin:8px 0 0"><input type="checkbox" name="remove_logo" value="1"> hapus logo</label>
                @endif
                <input type="file" name="logo" accept="image/*">
            </div>
        </div>

        <div class="card">
            <h3 class="cardh">Kontak</h3>
            <label>Alamat
                <textarea name="address" rows="2">{{ old('address', $v['address'] ?? '') }}</textarea>
            </label>
            <div class="grid2">
                <label>Telepon <input type="text" name="phone" value="{{ old('phone', $v['phone'] ?? '') }}"></label>
                <label>Email marketing <input type="text" name="email_marketing" value="{{ old('email_marketing', $v['email_marketing'] ?? '') }}"></label>
            </div>
            <label>Jam kerja <input type="text" name="working_hours" value="{{ old('working_hours', $v['working_hours'] ?? '') }}"></label>
            <label>ID video profil YouTube <input type="text" name="youtube_video_id" maxlength="11" value="{{ old('youtube_video_id', $v['youtube_video_id'] ?? '') }}" placeholder="contoh: nYarnSGbWqE (11 karakter di link YouTube)"></label>
            <div class="grid2">
                <label>Instagram: jumlah postingan <input type="text" name="ig_posts" value="{{ old('ig_posts', $v['ig_posts'] ?? '') }}" placeholder="2.641"></label>
                <label>Instagram: pengikut <input type="text" name="ig_followers" value="{{ old('ig_followers', $v['ig_followers'] ?? '') }}" placeholder="109 rb"></label>
                <label>Instagram: mengikuti <input type="text" name="ig_following" value="{{ old('ig_following', $v['ig_following'] ?? '') }}" placeholder="525"></label>
            </div>
        </div>

        <div class="card">
            <h3 class="cardh">Link sosial media</h3>
            <div class="grid2">
                <label>Instagram <input type="text" name="social_instagram" value="{{ old('social_instagram', $v['social_instagram'] ?? '') }}" placeholder="https://instagram.com/…"></label>
                <label>Facebook <input type="text" name="social_facebook" value="{{ old('social_facebook', $v['social_facebook'] ?? '') }}" placeholder="https://facebook.com/…"></label>
                <label>YouTube <input type="text" name="social_youtube" value="{{ old('social_youtube', $v['social_youtube'] ?? '') }}" placeholder="https://youtube.com/…"></label>
                <label>TikTok <input type="text" name="social_tiktok" value="{{ old('social_tiktok', $v['social_tiktok'] ?? '') }}" placeholder="https://tiktok.com/@…"></label>
                <label>LinkedIn <input type="text" name="social_linkedin" value="{{ old('social_linkedin', $v['social_linkedin'] ?? '') }}" placeholder="https://linkedin.com/company/…"></label>
            </div>
            <p class="mu" style="font-size:13px;margin:0">Kosongkan yang tidak dipakai; link yang kosong tidak ditampilkan di footer.</p>
        </div>

        <div class="card">
            <h3 class="cardh">SEO (tampil di hasil pencarian Google)</h3>
            <x-lang-tabs name="meta_description" label="Deskripsi singkat situs (maks. 160 karakter)" type="textarea" :rows="3"
                         :values="$metaDescription" help="Terjemahan diisi manual. Jika kosong, dipakai Bahasa Indonesia." />
            <label>Kata kunci <small>pisahkan dengan koma</small>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $v['meta_keywords'] ?? '') }}">
            </label>
        </div>

        <div class="sp-save"><button type="submit" class="b">Simpan pengaturan</button></div>
    </form>
@endsection
