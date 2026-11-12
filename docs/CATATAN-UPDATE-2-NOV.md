# Catatan pengembangan — penggabungan fitur `comprof` ke `ComprofArmada`

Basis = **ComprofArmada terbaru** (branch `main`, sudah berisi plant, kontak, halaman berita, kategori/produk). Fitur dari folder `comprof` digabungkan ke dalamnya.
Perubahan belum di-commit: lihat `git status` / `git diff`, lalu commit di branch sendiri (mis. `feature/social-bahasa`).

## 1. Instagram & Sosial Media (API Management)
- Menu sidebar **Instagram** (`/admin/social`). Satu kotak input menerima: **link share**, **kode embed/oEmbed (`<iframe …>` / blockquote)**, atau **JSON oEmbed**.
- Platform: Instagram, Facebook, TikTok, YouTube (+ LinkedIn lewat kode embed).
- **JSON bisa diedit manual**: ⋯ → *Edit JSON* (validasi langsung, tombol Rapikan, ditolak bila `embed_url` bukan domain resmi).
- Facebook: link share `/share/r|v|p/…`, `fb.watch`, `fb.me` diikuti pengalihannya dari server; jika server tidak bisa, dicoba dengan link aslinya. Bila tetap gagal → pakai kode embed.
- Keamanan: HTML yang ditempel tidak disimpan mentah; hanya `src` + ukuran diambil, whitelist domain, iframe dibangun ulang sistem.
- Tampilan Home: section bergaya Instagram (latar biru, judul besar, carousel) berisi embed + tombol Follow Instagram.
- File: `app/Services/OembedService.php`, `Admin/SocialPostController`, `SocialFeedController`, `resources/views/admin/social/index.blade.php`, `partials/social-embeds.blade.php`, migration `social_posts` + `add_embed_width…`.
- JSON publik: `GET /api/social`.

## 2. Tiga bahasa (ID / EN / 中文) tersambung ke admin
- Pemilih bahasa + ikon cari di header semua halaman publik (Home, Produk, Detail, Berita, Kontak); teks UI di `lang/{id,en,zh}/site.php`.
- Admin sudah mendukung terjemahan untuk: **Banner** (perbaikan: kolom `*_zh` sebelumnya tidak tersimpan), **Berita** (sudah ada, kini dipakai di web), **Kategori**, **Produk** (subjudul, deskripsi), **Lokasi Plant** (nama, alamat) — memakai komponen tab bahasa `<x-lang-tabs>`.
- Terjemahan kosong → otomatis memakai Bahasa Indonesia. Terjemahan Mandarin pada `lang/zh` adalah draf; mohon dicek penutur asli.
- Migration baru: `2026_11_02_000002_add_translations_to_categories_products_plants`.

## 3. Gambar → storage
- Folder `company-profile/images` → `storage/app/public/images/` (sudah dikompres: 52 MB → 7 MB, maks. lebar 1600 px; aslinya tetap ada di folder `company-profile`).
- Dilayani lewat `public/storage` (`php artisan storage:link`). `storage/app/public/.gitignore` diubah agar folder `images/` ikut ter-commit.
- `News::fileUrl()` dan logo di header sekarang membaca `storage/images/…`; `CategorySeeder` mengisi gambar poster kategori. File bawaan tidak ikut terhapus saat kategori/produk dihapus.

## 3b. Tampilan Home disamakan dengan `company-profile/index.html` (revisi kedua)
- CSS Home = CSS prototype (header logo gambar + teks NEW ARMADA, video profil, carousel Instagram).
- Hero: bila tabel banner kosong, tampil 2 slide bawaan prototype. Isi banner awal: `php artisan db:seed --class=BannerSeeder` (hanya jalan jika tabel kosong).
- Bisnis Unit: video profil YouTube dengan thumbnail + tombol play (ID video bisa diganti di Pengaturan Situs).
- Instagram: carousel looping dengan kartu memudar di pinggir, sama seperti prototype. Kartu diambil dari menu admin Instagram (reel Instagram dipotong seperti prototype; Facebook/TikTok/YouTube diskalakan memenuhi kartu). Jika admin masih kosong, dipakai 6 reel contoh dari prototype. Angka postingan/pengikut/mengikuti diatur di Pengaturan Situs.
- Poster kategori: bila kategori belum punya gambar, dipakai gambar bawaan di `storage/images` sesuai slug (big-bus, medium-bus, intercity, microbus, box-heavy-duty).
- **Semua gambar bergantung pada `php artisan storage:link`.** Jika gambar tidak muncul, cek `public/storage` ada.

## 4. Dibawa dari comprof
Pengaturan Situs (`/admin/settings`: logo, alamat, telepon, sosmed, SEO → footer/Kontak/meta), Pencarian (`/cari`, saran langsung), panduan marketing.

## Cara uji (belum dijalankan oleh saya — PHP tidak tersedia saat penyusunan)
```bash
composer install
cp .env.example .env && php artisan key:generate      # isi DB_* MySQL
php artisan migrate
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=PlantSeeder
php artisan db:seed --class=NewsSeeder
php artisan db:seed --class=SettingSeeder
php artisan db:seed --class=BannerSeeder      # hanya jika tabel banner masih kosong
php artisan storage:link
php artisan optimize:clear
php artisan test --filter=SocialAndLanguageTest
php artisan serve
```
Jangan menjalankan `php artisan db:seed` tanpa `--class` bila user admin sudah ada.

Checklist manual: (1) `/admin/social` → tempel kode iframe Facebook dari contoh → muncul di Home bagian Instagram; (2) tempel link share TikTok & Facebook; (3) ⋯ → Edit JSON, ubah `height`, simpan; (4) ganti bahasa ID/EN/中文 di Home, Produk, Berita, Kontak; (5) isi terjemahan di Kategori/Produk/Plant/Banner lalu cek di web; (6) gambar kategori & berita tampil.


## 5. Cocokkan dengan database `comprofarmada.sql` (revisi ketiga)
Isi dump menunjukkan penyebab banyak elemen tidak tampil:
1. **Tabel `categories` dan `product_models` kosong** → bagian Kategori/Produk kosong. Isi dengan: `php artisan db:seed --class=CatalogSeeder` (5 kategori + 20 model dari prototype; aman diulang).
2. **Banner/berita menunjuk file unggahan** (`banners/xxx.jpg`, `news/xxx.webp`). Database hanya menyimpan *nama/path* file; filenya ada di `storage/app/public/banners` dan `storage/app/public/news` di komputer yang dipakai mengunggah, dan **tidak ikut Git**. `php artisan storage:link` hanya membuat pintu `public/storage` → `storage/app/public`; ia tidak membuat/menyalin file. Salin kedua folder itu dari komputer pengunggah, atau unggah ulang lewat admin. Sementara file belum ada, web memakai gambar bawaan di `storage/images` (tidak kosong lagi).
3. **Beberapa tabel sudah ada tetapi tidak tercatat di tabel `migrations`** (plants, settings, social_posts) → `migrate` bisa gagal "table already exists". Semua migration yang relevan kini dilewati bila tabel/kolom sudah ada, jadi `php artisan migrate` aman dijalankan pada database ini.
4. Banner id 3 pada dump adalah data uji ("label / judul / gradasi") — hapus atau ubah lewat admin Banner.

Urutan pada database yang sudah diimpor:
```bash
composer install && php artisan key:generate   # bila .env baru
php artisan migrate
php artisan db:seed --class=CatalogSeeder
php artisan storage:link                       # bila public/storage berupa folder biasa, hapus dulu
php artisan optimize:clear
```


## 6. TikTok, geser otomatis, logo (revisi keempat)
- TikTok memakai **pemutar resmi** `tiktok.com/player/v1/ID` (hanya video, tanpa "related videos"/judul/musik), jadi kartunya bersih seperti Instagram. Data TikTok lama (embed/v2) otomatis memakai pemutar ini. Kode embed `<blockquote class="tiktok-embed" …>` dan link share sama-sama diterima.
- Catatan: pemutar TikTok hanya jalan untuk video yang diizinkan pemiliknya untuk di-embed. Jika kartu tetap kosong, buka video asli → ⋯ → pastikan "Allow embed"/"Izinkan sematkan" aktif.
- Carousel Instagram **bergeser otomatis** tiap 4,5 detik dengan transisi 1 detik. Berhenti saat kursor berada di atas carousel, saat disentuh di HP, saat tab tidak aktif, atau carousel tidak terlihat. Ubah di `welcome.blade.php`: `IG_AUTOROTATE`, `IG_INTERVAL`.
- Tulisan logo "NEW ARMADA" di header memakai Montserrat Bold, huruf kapital, jarak antar huruf lebar (`public/css/site.css`, kelas `.brand-text`).


## 7. Digabung dengan update tim (commit `9c028dd`) + penggantian nama file
- Basis sekarang = update tim: kontak plant (`phones`, `email`, `working_hours`, `is_hq`), lang `home.php`, halaman Kontak dari data HQ, gambar artikel berita, teks admin Instagram yang disederhanakan.
- Perubahan tim dipertahankan. Tambahan kecil: `data-word` pada kartu layanan diberi tanda kutip (tanpa kutip, teks bahasa lain bisa merusak atribut), migration plant tim dibuat aman diulang, carousel Instagram memakai jeda 3,5 detik sesuai pengaturan tim.
- File ditata ulang dengan nama netral: `public/css/site.css`, `public/js/site.js`, `public/css/admin-extra.css` (sebelumnya memakai nama pribadi). Semua view sudah diperbarui; hapus file lama bila masih ada di repo lokal: `public/css/gustam-public.css`, `public/js/gustam-public.js`, `public/css/admin-gustam.css`.


## 8. Carousel Instagram: tombol & logo (revisi kelima)
- Tombol geser manual ada di sisi kiri dan kanan carousel, agak transparan; saat kursor diarahkan, isi putih membesar dari tengah (`.ig-nav` di `public/css/site.css`).
- Tombol "Follow Instagram" di tengah; saat kursor diarahkan, isi putih naik dari bawah (`.ig-follow`).
- Judul "NEW ARMADA" di section Instagram dan di halaman login admin sekarang memakai logo terbaru (gambar logo + Montserrat).
- **Geser otomatis**: prototype punya aturan "kurangi animasi" (`prefers-reduced-motion`) yang mematikan semua transisi, dan skrip ikut membatalkan geser otomatis di perangkat yang mengaktifkannya (umum di Windows bila "Show animations" dimatikan). Sekarang carousel tetap bergeser; ubah `IG_RESPECT_REDUCED_MOTION=true` di `welcome.blade.php` (dan hapus blok `prefers-reduced-motion` di akhir `site.css`) bila ingin patuh penuh.
- Geser berhenti sementara saat kursor di atas carousel, saat video diklik (12 detik), saat tab tidak aktif, atau carousel di luar layar.

## 10. Klik kartu carousel Instagram: putar dulu, klik lagi buka sumber (sesuai tim)
- **Klik 1** pada kartu = memutar video (klik terjadi di dalam iframe Instagram/TikTok/Facebook/YouTube). Skrip mendeteksi fokus pindah ke iframe, lalu kartu berstatus `playing`: geser otomatis berhenti dan muncul tombol "Buka di <platform> ↗" di bawah kartu.
- **Klik 2** pada kartu = membuka link sumber asli di tab baru (lapisan `.ig-open`, link dari `source_url` di menu admin).
- Video dihentikan (iframe dimuat ulang) saat carousel digeser, saat link dibuka, atau 15 detik setelah kursor meninggalkan carousel.
- Klik kartu redup di tepi tetap hanya menggeser carousel.
- Catatan: browser tidak mengizinkan membaca klik di dalam iframe lintas situs, jadi "klik 1" dideteksi lewat perpindahan fokus; di sebagian browser (mis. Safari iOS) deteksi ini bisa tidak terpicu — klik 2 lalu tetap berada di dalam iframe.

## 11. Carousel Instagram — versi akhir (rotasi & klik ikut tim, tombol ikut lokal)
- **Rotasi, geser otomatis (3,5 detik), swipe, dan klik kartu**: sama persis dengan commit terbaru tim (carousel looping 4 kartu + 2 kartu redup di tepi, 6 kartu). Klik kartu redup di tepi menggeser carousel dan membuka link sumber (`<a class="ig-hit">`; link dari `source_url` postingan di admin). Klik pada kartu utama ditangani embed (putar/jeda).
- **Tombol geser manual**: di sisi kiri & kanan carousel, agak transparan, isi putih membesar dari tengah saat kursor diarahkan (`.ig-nav` di `public/css/site.css`; di layar ≤ 900 px tombol berada di dalam area carousel).
- **Tombol Follow Instagram**: di tengah, isi putih naik dari bawah saat kursor diarahkan (`.ig-follow`).
- Logo "NEW ARMADA" di section ini = logo terbaru (gambar + Montserrat).
- **Hover kartu profil Instagram (Safari)**: area kiri-bawah kartu Instagram ditutup lapisan transparan (`.ig-card.p-instagram::after`, ukuran `--shield-w` / `--shield-h`) agar kartu profil bawaan Instagram tidak muncul.
- Dibatalkan: carousel silinder 3D, geser tak berujung + antrean spam, klik 1 putar / klik 2 buka, dan klik cepat 2x membuka sumber.
