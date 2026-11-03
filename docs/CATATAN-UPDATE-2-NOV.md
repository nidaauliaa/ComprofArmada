# Catatan update (Gustam) — penggabungan `comprof` ke `ComprofArmada`

Basis = **ComprofArmada terbaru** (branch `main`, sudah berisi plant, kontak, halaman berita, kategori/produk). Fitur milik Gustam dari folder `comprof` digabungkan ke dalamnya.
Perubahan belum di-commit: lihat `git status` / `git diff`, lalu commit di branch sendiri (mis. `feature/gustam-pic`).

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
