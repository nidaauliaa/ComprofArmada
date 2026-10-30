<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Tourista 25 – Detail Produk New Armada</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blue-primary:#3b82f6;--red-primary:#ff5733;--bg-light:#f8fafc;--ink-primary:#0f172a;--ink-muted:#64748b;--line:#e2e8f0;--hd:'Barlow Condensed',sans-serif;--bd:'Barlow',sans-serif}
*,*::before,*::after{box-sizing:border-box}
body{margin:0;background:var(--bg-light);color:var(--ink-primary);font:400 16px/1.6 var(--bd);overflow-x:hidden}
a{color:inherit;text-decoration:none}

/* KONTAINER DESKTOP DENGAN PADDING LEGA */
.wrap{max-width:1400px;margin:0 auto;padding:0 48px}

/* BREADCRUMB */
.breadcrumb{padding:32px 0 16px;font-size:14px;color:var(--ink-muted);font-weight:500}
.breadcrumb a{color:var(--ink-primary);font-weight:600}

/* SECTION DETAIL UTAMA */
.detail-section{padding:20px 0 80px}
.detail-grid{display:grid;grid-template-columns:1.1fr 1.2fr 1.4fr;gap:48px;align-items:center}

@media(max-width:1100px){
  .detail-grid{grid-template-columns:1fr;gap:40px}
  .wrap{padding:0 24px}
}

/* 1. KOLOM KIRI: Deskripsi & Aksi */
.detail-info h1{font:800 clamp(40px,4.5vw,56px)/1.05 var(--hd);text-transform:uppercase;margin:0 0 18px;letter-spacing:-.01em}
.detail-info p{font-size:16px;line-height:1.75;color:var(--ink-muted);margin:0 0 32px}
.detail-actions{display:flex;gap:14px;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;justify-content:center;background:var(--blue-primary);color:#fff;padding:14px 28px;border-radius:8px;font-weight:600;font-size:15px;border:none;cursor:pointer;transition:.25s ease;box-shadow:0 4px 14px rgba(59,130,246,.2)}
.btn:hover{background:#2563eb;transform:translateY(-2px)}
.btn.btn-orange{background:#d97706;box-shadow:0 4px 14px rgba(217,119,6,.2)}
.btn.btn-orange:hover{background:#b45309}

/* 2. KOLOM TENGAH: Kotak Spesifikasi Gelap */
.spec-box{background:#111c30;color:#fff;border-radius:20px;padding:36px;box-shadow:0 24px 50px rgba(15,23,42,.2)}
.spec-title{font:700 18px var(--hd);letter-spacing:.1em;text-transform:uppercase;margin-bottom:20px;color:#93c5fd;border-bottom:1px solid rgba(255,255,255,.1);padding-bottom:12px}
.spec-row{display:flex;justify-content:space-between;padding:13px 0;border-bottom:1px solid rgba(255,255,255,.08);font-size:14px}
.spec-row:last-child{border-bottom:none}
.spec-label{color:#94a3b8;text-transform:uppercase;font-weight:600;letter-spacing:.05em}
.spec-val{font-weight:700;letter-spacing:.03em;color:#fff}
.spec-badge{margin-top:24px;text-align:center}

/* 3. KOLOM KANAN: Visual Bus & Lingkaran Proporsional */
.visual-box{position:relative;display:flex;justify-content:center;align-items:center;min-height:420px}
.circle-backdrop{position:absolute;width:420px;height:420px;background:radial-gradient(circle, #e2e8f0 0%, #cbd5e1 100%);border-radius:50%;z-index:0;box-shadow:inset 0 10px 20px rgba(255,255,255,.6)}
.visual-box img{position:relative;z-index:1;width:100%;max-width:480px;height:auto;object-fit:contain;filter:drop-shadow(0 20px 30px rgba(15,23,42,.25));transition:transform .4s ease}
.visual-box:hover img{transform:scale(1.03)}

/* GALERI FOTO BAWAH */
.gallery-section{padding:60px 0 100px;border-top:1px solid var(--line);background:#fff}
.gallery-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:32px}
.gallery-title{font:800 28px/1 var(--hd);text-transform:uppercase;margin:0}
.gallery-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px}
.gallery-item{border-radius:14px;overflow:hidden;background:var(--bg-light);aspect-ratio:16/10;box-shadow:0 10px 25px rgba(15,23,42,.06);border:1px solid var(--line);cursor:pointer}
.gallery-item img{width:100%;height:100%;object-fit:cover;transition:transform .4s ease}
.gallery-item:hover img{transform:scale(1.06)}
</style>
</head>
<body>

<div class="wrap">
  <div class="breadcrumb">
    <a href="{{ url('/') }}">Beranda</a> › <a href="{{ route('product.index') }}">Produk</a> › <span>Medium Bus</span> › <b>Tourista 25</b>
  </div>
</div>

<section class="detail-section">
  <div class="wrap">
    <div class="detail-grid">

      <!-- 1. KIRI: Teks Informasi & Tombol Aksi -->
      <div class="detail-info">
        <h1>Tourista 25</h1>
        <p>Tourista 25 hadir dengan identitas visual baru, fascia depan yang diperbarui, tata lampu LED modern, serta garis bodi yang dinamis untuk memberikan daya tarik visual, visibilitas tinggi, ketangguhan maksimal, dan kenyamanan paripurna pada operasional pariwisata Anda.</p>
        <div class="detail-actions">
          <button class="btn">Unduh Brosur</button>
          <button class="btn btn-orange">Hubungi Sales</button>
        </div>
      </div>

      <!-- 2. TENGAH: Spesifikasi Teknis (Kotak Elegan) -->
      <div class="spec-box">
        <div class="spec-title">Spesifikasi Utama</div>
        <div class="spec-row"><span class="spec-label">Brand</span><span class="spec-val">NEW ARMADA</span></div>
        <div class="spec-row"><span class="spec-label">Model</span><span class="spec-val">TOURISTA 25</span></div>
        <div class="spec-row"><span class="spec-label">Height</span><span class="spec-val">3,120 MM</span></div>
        <div class="spec-row"><span class="spec-label">Width</span><span class="spec-val">2,280 MM</span></div>
        <div class="spec-row"><span class="spec-label">Length</span><span class="spec-val">7,400 MM</span></div>
        <div class="spec-badge">
          <span style="display:inline-block;background:rgba(22,101,52,.3);color:#4ade80;border:1px solid rgba(74,222,128,.3);padding:6px 16px;border-radius:20px;font-size:12px;font-weight:700;letter-spacing:.05em">✓ TERSERTIFIKASI KUALITAS</span>
        </div>
      </div>

      <!-- 3. KANAN: Gambar Bus dalam Backdrop Lingkaran Proporsional -->
      <div class="visual-box">
        <div class="circle-backdrop"></div>
        <img src="{{ asset('images/MEDIUM-BUS.png') }}" alt="Tourista 25">
      </div>

    </div>
  </div>
</section>

<!-- GALERI FOTO BAWAH -->
<section class="gallery-section">
  <div class="wrap">
    <div class="gallery-head">
      <h2 class="gallery-title">Galeri Model &amp; Interior</h2>
    </div>
    <div class="gallery-grid">
      <div class="gallery-item"><img src="{{ asset('images/MEDIUM-BUS.png') }}" alt="Interior 1" loading="lazy"></div>
      <div class="gallery-item"><img src="{{ asset('images/BIGBUS-DOUBLEDECKER.png') }}" alt="Interior 2" loading="lazy"></div>
      <div class="gallery-item"><img src="{{ asset('images/INTERCITY-2.png') }}" alt="Seat Detail" loading="lazy"></div>
      <div class="gallery-item"><img src="{{ asset('images/MICROBUS-2.png') }}" alt="Headlamp Detail" loading="lazy"></div>
      <div class="gallery-item"><img src="{{ asset('images/BOX.png') }}" alt="AC Detail" loading="lazy"></div>
    </div>
  </div>
</section>

</body>
</html>
