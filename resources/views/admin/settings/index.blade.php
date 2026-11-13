@extends('layouts.admin')

@section('title', 'Pengaturan Situs')
@section('page_icon', 'gear')
@section('page_title', 'Pengaturan Situs')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-gustam.css') }}">
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

                    <label style="font-weight:500;margin:8px 0">
                        <input type="checkbox" name="remove_logo" value="1">
                        Hapus logo
                    </label>
                @endif

                <input type="file" name="logo"
                    accept="image/png,image/jpeg,image/webp,image/svg+xml">
            </div>

            <p class="mu" style="font-size:13px">
                Tulisan NEW ARMADA tetap ditampilkan meskipun logo belum diunggah.
            </p>
        </div>

        <div class="card">
            <h3 class="cardh">Media & Instagram</h3>
            <div style="margin-bottom:14px">
                <b>Upload video profil</b>
                <small class="mu">MP4 (disarankan), WebM, atau MOV. Maks. 300 MB. Kalau diisi, video ini yang tampil dan link YouTube diabaikan.</small>

                @if (!empty($v['profile_video']))
                    <video src="{{ asset('storage/'.$v['profile_video']) }}" controls preload="metadata"
                        style="display:block;max-width:360px;width:100%;margin:12px 0;border-radius:8px"></video>
                    <label style="font-weight:500;margin:8px 0">
                        <input type="checkbox" name="remove_profile_video" value="1"> Hapus video
                    </label>
                @endif

                <input type="file" name="profile_video" accept="video/mp4,video/webm,video/quicktime">
            </div>

<label>
    Atau pakai link video YouTube
    <input
        type="url"
        name="youtube_video_id"
        value="{{ old('youtube_video_id', !empty($v['youtube_video_id']) ? 'https://youtu.be/'.$v['youtube_video_id'] : '') }}"
        placeholder="https://youtu.be/xxxxxxxxxxx"
    >
    <small class="mu">Dipakai kalau tidak ada video yang diupload.</small>
</label>

            <div class="grid2">
                <label>
                    Instagram: jumlah postingan
                    <input type="text" name="ig_posts"
                        value="{{ old('ig_posts', $v['ig_posts'] ?? '') }}"
                        placeholder="2.641">
                </label>

                <label>
                    Instagram: pengikut
                    <input type="text" name="ig_followers"
                        value="{{ old('ig_followers', $v['ig_followers'] ?? '') }}"
                        placeholder="109 rb">
                </label>

                <label>
                    Instagram: mengikuti
                    <input type="text" name="ig_following"
                        value="{{ old('ig_following', $v['ig_following'] ?? '') }}"
                        placeholder="525">
                </label>
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
