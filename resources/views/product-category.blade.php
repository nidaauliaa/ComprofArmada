<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Produk – New Armada</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blue-primary:#3b82f6;--red-primary:#ff5733;--bg-light:#f8fafc;--ink-primary:#0f172a;--ink-muted:#64748b;--line:#e2e8f0;--hd:'Barlow Condensed',sans-serif;--bd:'Barlow',sans-serif}
body{margin:0;background:var(--bg-light);color:var(--ink-primary);font:400 16px/1.6 var(--bd)}
a{color:inherit;text-decoration:none}
.wrap{max-width:1320px;margin:0 auto;padding:0 32px}

/* TOP BREADCRUMB NAV */
.prod-subnav{background:#fff;border-bottom:1px solid var(--line);padding:24px 0}
.breadcrumb{display:inline-flex;align-items:center;gap:8px;background:var(--bg-light);padding:8px 18px;border-radius:30px;font-size:14px;font-weight:600;color:var(--ink-muted);border:1px solid var(--line);margin-bottom:28px}
.breadcrumb a{color:var(--ink-primary)}

/* TABS KATEGORI */
.cat-tabs{display:flex;gap:32px;border-bottom:2px solid var(--line);margin-bottom:48px;overflow-x:auto}
.cat-tab{font:700 18px/1 var(--hd);text-transform:uppercase;color:var(--ink-muted);padding-bottom:14px;position:relative;white-space:nowrap}
.cat-tab.active{color:var(--ink-primary)}
.cat-tab.active::after{content:"";position:absolute;bottom:-2px;left:0;width:100%;height:3px;background:var(--ink-primary)}

/* HEADER SECTION */
.sec-title{text-align:center;margin-bottom:48px}
.sec-title h1{font:800 clamp(36px,4vw,52px)/1.1 var(--hd);text-transform:uppercase;margin:0 0 10px}
.sec-title p{color:var(--ink-muted);margin:0}

/* GRID MODEL BUS */
.model-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;margin-bottom:80px}
.model-card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:32px;display:flex;flex-direction:column;justify-content:space-between;min-height:220px;transition:all .3s ease;box-shadow:0 10px 30px rgba(15,23,42,.04)}
.model-card:hover{transform:-translateY(4px);border-color:var(--blue-primary);box-shadow:0 20px 40px rgba(59,130,246,.1)}
.model-card h3{font:700 24px/1.2 var(--hd);text-transform:uppercase;margin:0 0 8px}
.model-card p{color:var(--ink-muted);font-size:14px;margin:0 0 24px}
.model-link{font-weight:600;color:var(--ink-primary);display:inline-flex;align-items:center;gap:6px;position:relative}
.model-link::after{content:"";position:absolute;bottom:-2px;left:0;width:100%;height:2px;background:var(--red-primary)}
</style>
</head>
<body>

<div class="prod-subnav">
  <div class="wrap">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">🏠 Beranda</a> › <a href="{{ route('product.index') }}">Produk</a> › <span>{{ $currentCategoryName ?? 'Medium Bus' }}</span>
    </div>

    <div class="cat-tabs">
      <a href="{{ url('/produk?kategori=big-bus') }}" class="cat-tab {{ ($kategori ?? '') == 'big-bus' ? 'active' : '' }}">Big Bus</a>
      <a href="{{ url('/produk?kategori=medium-bus') }}" class="cat-tab {{ ($kategori ?? '') == 'medium-bus' ? 'active' : '' }}">Medium Bus</a>
      <a href="{{ url('/produk?kategori=intercity') }}" class="cat-tab {{ ($kategori ?? '') == 'intercity' ? 'active' : '' }}">Intercity</a>
      <a href="{{ url('/produk?kategori=microbus') }}" class="cat-tab {{ ($kategori ?? '') == 'microbus' ? 'active' : '' }}">Microbus</a>
      <a href="{{ url('/produk?kategori=box-heavy-duty') }}" class="cat-tab {{ ($kategori ?? '') == 'box-heavy-duty' ? 'active' : '' }}">Box &amp; Heavy Duty</a>
    </div>
  </div>
</div>

<div class="wrap" style="padding-top:40px;">
  <div class="sec-title">
    <h1>Model {{ $currentCategoryName ?? 'Medium Bus' }}</h1>
    <p>Pilih model untuk melihat detail dan spesifikasinya.</p>
  </div>

  <div class="model-grid">
    <!-- Contoh Card Model (Nanti bisa diloop dari Database Model produk) -->
    <a href="{{ url('/produk/tourista-25') }}" class="model-card">
      <div>
        <h3>Tourista 25</h3>
        <p>Pariwisata · 25 kursi</p>
      </div>
      <span class="model-link">Lihat Model</span>
    </a>
    <a href="{{ url('/produk/tourista-29') }}" class="model-card">
      <div>
        <h3>Tourista 29</h3>
        <p>Pariwisata · 29 kursi</p>
      </div>
      <span class="model-link">Lihat Model</span>
    </a>
    <a href="{{ url('/produk/tourista-33') }}" class="model-card">
      <div>
        <h3>Tourista 33</h3>
        <p>Pariwisata · 33 kursi</p>
      </div>
      <span class="model-link">Lihat Model</span>
    </a>
    <a href="{{ url('/produk/tourista-35-executive') }}" class="model-card">
      <div>
        <h3>Tourista 35 Executive</h3>
        <p>Executive · 35 kursi</p>
      </div>
      <span class="model-link">Lihat Model</span>
    </a>
  </div>
</div>

</body>
</html>
