# Panduan Admin untuk Tim Marketing

Alamat admin: `https://domain-anda/admin` (login dengan email & password dari IT).

---

## 1. Menampilkan konten Instagram / Facebook / TikTok / YouTube di web (paling sering dipakai)

Buka menu **Instagram** di sidebar. Di kotak input, tempel **salah satu** dari ini, lalu klik **Tampilkan di web**:

1. **Link share biasa** — postingan/reel/video → tombol Bagikan → Salin link.
2. **Kode embed (oEmbed)** — postingan → ⋯ → **Embed / Sematkan** → salin kode yang diawali `<iframe …>`.
   Pakai cara ini jika link share Facebook/TikTok gagal atau jika situs tertentu diblokir di jaringan kantor.
3. **JSON oEmbed** — bila IT memberikan JSON, tempel langsung.

Contoh kode embed Facebook yang diterima:
`<iframe src="https://www.facebook.com/plugins/video.php?height=476&href=…&width=267&t=0" width="267" height="591" …></iframe>`

Setelah tampil, tombol **⋯** di tiap baris punya: **Edit JSON** (ubah judul, ukuran, alamat embed secara manual), **Pratinjau**, naik/turun urutan, buka link asli, hapus. Saklar = tampilkan/sembunyikan.

Catatan: akun/konten harus **publik**. Demi keamanan hanya alamat embed dari Instagram, Facebook, TikTok, YouTube, dan LinkedIn yang diterima; script lain di kode embed dibuang otomatis.

| Pesan error | Artinya / solusi |
|---|---|
| "JSON tidak valid" | Ada tanda kutip/koma yang kurang. Tombol **Rapikan** di editor membantu mengecek. |
| "Alamat embed … bukan dari Instagram, Facebook, …" | Kode embed berasal dari situs lain. Salin ulang dari platform aslinya. |
| "Gunakan link postingan atau reel…" | Link profil/story Instagram tidak bisa. Pakai link postingan/reel. |
| Link Facebook/TikTok share gagal | Pakai cara ke-2 (kode embed). |

## 2. Ganti bahasa (ID | EN | 中文)

Di bagian atas website ada pilihan bahasa. Isi tiap bahasa **diketik manual** (bukan terjemahan otomatis).
Di form admin yang punya tab **ID · EN · 中文**: klik tab untuk mengisi bahasa lain.
Titik oranye pada tab = bahasa itu masih kosong. Jika dikosongkan, website menampilkan Bahasa Indonesia.

## 3. Pengaturan Situs (logo, alamat, telepon, sosmed, SEO)

Menu **Pengaturan Situs** → ubah → **Simpan pengaturan**. Data ini tampil di footer website dan di hasil Google.
- Link sosmed yang dikosongkan tidak ditampilkan di footer.
- Deskripsi singkat (SEO) maksimal 160 karakter, tersedia per bahasa.

## 4. Pencarian di website

Pengunjung klik ikon kaca pembesar di header → ketik nama produk/berita. Tidak ada yang perlu diatur marketing;
hasil otomatis dari data Produk dan Berita yang berstatus aktif.

## 5. Banner, Berita, Produk
Dikelola di menu masing-masing (panduan oleh tim pembuat menu tersebut).

---

## Tanya jawab singkat
- **Konten sosmed tidak berubah otomatis mengikuti post terbaru.** Yang tampil hanya yang dipilih marketing.
- **Kalau postingan dihapus di Instagram/Facebook/TikTok**, kotaknya di web akan kosong → hapus dari menu Instagram.
- **Lupa password?** Hubungi IT.
