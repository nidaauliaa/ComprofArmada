@php
    // Teks tetap di halaman ini: pakai terjemahan kalau key-nya sudah ada di lang/*/site.php, kalau belum pakai teks bawaan.
    $tr = fn ($key, $default) => \Illuminate\Support\Facades\Lang::has('site.' . $key) ? __('site.' . $key) : $default;

    $A = \App\Models\AboutPage::class;
    $d = $about->data();
    $t = fn ($k) => $A::pick($d[$k] ?? null);          // teks bahasa aktif (kosong -> Indonesia)
    $rich = fn ($s) => $A::rich((string) $s);          // **tebal** & *miring* (aman XSS)
    $photo = $about->photoUrl();

    $stats = collect($d['stats'] ?? [])
        ->map(fn ($s) => ['v' => $A::statValue($s), 'l' => $A::pick($s['label'] ?? null)])
        ->filter(fn ($s) => $s['v'] !== '' || $s['l'] !== '')->values();

    $paras = $A::paragraphs($t('overview_body'));
    $mission = $A::lines($t('mission'));
    $vision = $t('vision');

    $phil = collect($d['philosophy'] ?? [])->map(fn ($p) => $A::pick($p))->filter()->values();

    $hist = collect($d['history'] ?? [])
        ->map(fn ($h) => ['y' => $A::yearLabel($h), 't' => $A::pick($h['text'] ?? null)])
        ->filter(fn ($h) => $h['y'] !== '' || $h['t'] !== '')->values();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ __('site.about_us') }} – New Armada</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blue-primary:#3b82f6;--blue-hover:#2563eb;--red-primary:#ff5733;--red-hover:#e03e1b;--white:#fff;--bg-light:#f8fafc;--ink-primary:#0f172a;--ink-muted:#55627c;--line-color:#e2e8f0;--navy:#12274d;--bg-surface:#f4f6f9;--bg-dark:#0f172a;--accent-blue:#2563eb;--hd:'Barlow Condensed','Arial Narrow',sans-serif;--bd:'Barlow',system-ui,sans-serif}
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:#fff;color:var(--ink-primary);font:400 16px/1.7 var(--bd);overflow-x:hidden}
a{color:inherit;text-decoration:none}
button{font-family:inherit}
.wrap{width:100%;max-width:1320px;margin:0 auto;padding:0 32px}
section{padding:100px 0;position:relative}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--blue-primary);color:var(--white);padding:14px 30px;border-radius:8px;border:none;cursor:pointer;font:600 15px/1 var(--bd);transition:all .25s ease;box-shadow:0 4px 14px rgba(59,130,246,.25)}
.btn:hover{background:var(--blue-hover);transform:translateY(-2px)}
.btn.btn-red{background:var(--red-primary);box-shadow:0 4px 14px rgba(255,87,51,.25)}
.btn.btn-red:hover{background:var(--red-hover)}
a:focus-visible,button:focus-visible{outline:3px solid var(--red-primary);outline-offset:3px}

/* HEADER (sama dengan halaman Kontak: transparan, teks gelap karena latar terang) */
header{position:absolute;top:0;left:0;right:0;z-index:100;background:transparent}
.nav{display:flex;align-items:center;justify-content:space-between;height:76px}
.logo{font:800 26px/1 var(--hd);letter-spacing:.03em;display:flex;align-items:center;gap:10px;color:var(--navy)}
.logo-icon{width:14px;height:18px;background:var(--red-primary);transform:skewX(-18deg);border-radius:2px}
nav{display:flex;gap:32px;align-items:center;font-weight:600;font-size:15px}
nav a:not(.btn){position:relative;color:var(--navy);padding:6px 0;transition:color .25s ease}
nav a:not(.btn)::after{content:"";position:absolute;bottom:0;left:0;width:0;height:2px;background:var(--red-primary);transition:width .3s ease}
nav a:not(.btn):hover,nav a.cur:not(.btn){color:var(--red-primary)}
nav a:not(.btn):hover::after{width:100%}
.lang{font-size:13px;font-weight:600;color:var(--navy);background:#fff;padding:6px 12px;border-radius:20px;border:1px solid rgba(18,39,77,.15)}
@media(max-width:900px){nav a:not(.btn):not(.lang){display:none}}
.brand{display:flex;align-items:center;gap:12px;color:var(--navy)}
.brand img{display:block;height:52px;width:auto}
.brand-text{font:800 26px/1 var(--hd);letter-spacing:.03em}
@media(max-width:560px){.brand-text{display:none}}
body.about-page header{position:fixed!important;background:transparent;box-shadow:none}
body.about-page header .brand,
body.about-page header .brand-text,
body.about-page header nav a:not(.btn){color:var(--navy)!important;text-shadow:none!important}
body.about-page header nav a:not(.btn):hover,
body.about-page header nav a.cur:not(.btn){color:var(--red-primary)!important}
body.about-page header .brand img{filter:none!important}
body.about-page header .lang{color:var(--navy)!important;background:#fff!important;border:1px solid rgba(18,39,77,.15)!important;text-shadow:none!important}

/* ===== HALAMAN TENTANG KAMI ===== */
.about-hero{padding:150px 0 80px;background:var(--bg-surface);border-bottom:1px solid var(--line-color)}
.back-link{display:inline-flex;align-items:center;gap:6px;font:600 14px var(--bd);color:var(--ink-muted);margin-bottom:22px;transition:color .25s}
.back-link:hover{color:var(--red-primary)}
.hero-grid{display:grid;grid-template-columns:1.2fr 1fr;gap:60px;align-items:center}
.hero-badge{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;background:rgba(37,99,235,.08);color:var(--accent-blue);border-radius:30px;font:700 13px var(--hd);letter-spacing:.1em;text-transform:uppercase;margin-bottom:20px}
.hero-content h1{font:800 clamp(42px,5.5vw,68px)/1.05 var(--hd);text-transform:uppercase;margin:0 0 20px;letter-spacing:-.01em;color:var(--ink-primary)}
.hero-content h1 span{color:var(--red-primary)}
.hero-content p{color:var(--ink-muted);font-size:18px;margin:0;max-width:560px}
.hero-stats-cards{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
.stat-box{background:#fff;padding:32px;border-radius:16px;border:1px solid var(--line-color);box-shadow:0 10px 30px rgba(0,0,0,.03)}
.stat-box h3{font:800 40px/1 var(--hd);color:var(--accent-blue);margin:0 0 6px}
.stat-box p{margin:0;font-size:14px;color:var(--ink-muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em}

.overview-layout{display:grid;grid-template-columns:1fr 1.2fr;gap:80px;align-items:center}
.overview-layout.no-photo{grid-template-columns:1fr}
.director-feature{position:relative}
.director-photo-frame{border-radius:20px;overflow:hidden;background:linear-gradient(160deg,#dbe3ef,#b8c4d6);aspect-ratio:4/5;box-shadow:0 25px 50px rgba(0,0,0,.1);display:flex;align-items:flex-end;justify-content:center}
.director-photo-frame img{width:100%;height:100%;object-fit:cover;filter:grayscale(15%) contrast(1.05);transition:filter .5s}
.director-photo-frame:hover img{filter:grayscale(0)}
.director-photo-frame svg{width:60%;height:auto;fill:rgba(255,255,255,.55)}
.director-floating-badge{position:absolute;bottom:-20px;right:-20px;background:var(--bg-dark);color:#fff;padding:28px 36px;border-radius:16px;box-shadow:0 20px 40px rgba(0,0,0,.2)}
.director-floating-badge h4{font:700 20px var(--hd);margin:0 0 4px;text-transform:uppercase}
.director-floating-badge span{color:var(--red-primary);font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase}
.overview-text h2{font:800 clamp(32px,4vw,46px)/1.1 var(--hd);text-transform:uppercase;margin:0 0 24px;color:var(--ink-primary)}
.overview-text p{color:var(--ink-muted);margin:0 0 20px;font-size:17px;text-align:justify}
.overview-text strong{color:var(--ink-primary)}

.visimisi-section{background:var(--bg-surface);border-top:1px solid var(--line-color);border-bottom:1px solid var(--line-color)}
.vm-grid{display:grid;grid-template-columns:1.2fr 1.8fr;gap:32px}
.vm-grid.single{grid-template-columns:1fr}
.vm-box{background:#fff;padding:48px;border-radius:20px;border:1px solid var(--line-color);box-shadow:0 10px 30px rgba(0,0,0,.02)}
.vm-box h3{font:800 28px var(--hd);text-transform:uppercase;margin:0 0 20px;display:flex;align-items:center;gap:12px;color:var(--ink-primary)}
.vm-box h3::before{content:"";width:8px;height:24px;background:var(--red-primary);border-radius:4px}
.vm-box.visi h3::before{background:var(--accent-blue)}
.vm-box p{font-size:18px;font-style:italic;color:var(--ink-muted);line-height:1.6;margin:0}
.vm-box ul{margin:0;padding-left:20px;color:var(--ink-muted);font-size:16px;display:grid;gap:14px}
.vm-box li{line-height:1.5}

.sec-head{max-width:700px;margin:0 auto 60px;text-align:center}
.sec-head h2{font:800 clamp(36px,5vw,50px)/1 var(--hd);text-transform:uppercase;margin:0 0 12px;color:var(--ink-primary)}
.sec-head p{color:var(--ink-muted);margin:0;font-size:17px}

.phil-grid{display:grid;grid-template-columns:repeat(var(--n,5),1fr);gap:16px}
.phil-item{background:#fff;padding:28px 20px;border-radius:14px;border:1px solid var(--line-color);transition:all .3s ease;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between}
.phil-item::after{content:"";position:absolute;bottom:0;left:0;right:0;height:4px;background:var(--red-primary);transform:scaleX(0);transition:transform .3s ease;transform-origin:left}
.phil-item:hover{transform:translateY(-6px);box-shadow:0 20px 40px rgba(0,0,0,.06);border-color:transparent}
.phil-item:hover::after{transform:scaleX(1)}
.phil-item .num{font:800 32px/1 var(--hd);color:var(--accent-blue);margin-bottom:12px;display:block}
.phil-item p{margin:0;font-weight:600;font-size:14px;color:var(--ink-primary);line-height:1.4}

.history-section{background:var(--bg-surface);border-top:1px solid var(--line-color)}
.timeline-container{max-width:900px;margin:0 auto;position:relative}
.timeline-container::before{content:"";position:absolute;left:20px;top:10px;bottom:10px;width:2px;background:var(--line-color)}
.t-item{position:relative;padding-left:64px;margin-bottom:48px}
.t-item:last-child{margin-bottom:0}
.t-item::before{content:"";position:absolute;left:13px;top:6px;width:16px;height:16px;border-radius:50%;background:#fff;border:3px solid var(--red-primary);box-shadow:0 0 0 4px rgba(255,87,51,.15)}
.t-year{font:800 24px var(--hd);color:var(--accent-blue);margin-bottom:6px;letter-spacing:.03em}
.t-desc{font-size:16px;color:var(--ink-muted);line-height:1.6}
.t-desc strong{color:var(--ink-primary)}

@media(max-width:1100px){.phil-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:991px){
  .hero-grid,.overview-layout,.vm-grid{grid-template-columns:1fr;gap:40px}
  .about-hero{padding:120px 0 60px}
  section{padding:70px 0}
  .director-floating-badge{position:relative;bottom:0;right:0;margin-top:-30px;width:100%}
  .phil-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:600px){
  .wrap{padding:0 20px}
  .vm-box{padding:32px 24px}
  .phil-grid{grid-template-columns:1fr}
  .stat-box{padding:22px}
  .stat-box h3{font-size:32px}
}

/* FOOTER (sama dengan footer bersama) */
footer{background:#0b172c;color:#94a3b8;padding:70px 0 30px}
.footer-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:40px}
footer h4{font:700 18px var(--hd);color:var(--white);margin:0 0 16px;text-transform:uppercase;letter-spacing:.05em}
footer ul{list-style:none;margin:0;padding:0;display:grid;gap:10px;font-size:15px}
footer a:hover{color:var(--red-primary)}
.footer-bottom{margin-top:50px;padding-top:24px;border-top:1px solid rgba(255,255,255,.1);font-size:14px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
@media(max-width:900px){.footer-grid{grid-template-columns:1fr 1fr}}
@media(prefers-reduced-motion:reduce){*{transition:none!important;scroll-behavior:auto!important}}
</style>
<link rel="stylesheet" href="{{ asset('css/gustam-public.css') }}?v={{ @filemtime(public_path('css/gustam-public.css')) }}">
</head>
<body class="about-page">

@include('partials.public-header', ['brand' => 'img', 'cur' => 'about'])

<main>
  {{-- HERO --}}
  <section class="about-hero">
    <div class="wrap hero-grid">
      <div class="hero-content">
        <a class="back-link" href="{{ url('/') }}">← {{ $tr('back_home', 'Kembali ke Beranda') }}</a>
        @if ($t('hero_badge') !== '')<div class="hero-badge">{{ $t('hero_badge') }}</div>@endif
        <h1>{{ $t('hero_title') }} @if ($t('hero_accent') !== '')<span>{{ $t('hero_accent') }}</span>@endif</h1>
        @if ($t('hero_lead') !== '')<p>{{ $t('hero_lead') }}</p>@endif
      </div>
      @if ($stats->isNotEmpty())
      <div class="hero-stats-cards">
        @foreach ($stats as $s)
          <div class="stat-box">
            <h3>{{ $s['v'] }}</h3>
            <p>{{ $s['l'] }}</p>
          </div>
        @endforeach
      </div>
      @endif
    </div>
  </section>

  {{-- DIREKTUR & RINGKASAN --}}
  @if ($paras || $t('overview_title') !== '' || $photo || trim((string) ($d['director_name'] ?? '')) !== '')
  <section>
    @php $hasPerson = $photo || trim((string) ($d['director_name'] ?? '')) !== ''; @endphp
    <div class="wrap overview-layout {{ $hasPerson ? '' : 'no-photo' }}">
      @if ($hasPerson)
      <div class="director-feature">
        <div class="director-photo-frame">
          @if ($photo)
            <img src="{{ $photo }}" alt="{{ $d['director_name'] ?? '' }}" loading="lazy">
          @else
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0 2c-4.4 0-9 2.2-9 5.5V22h18v-2.5C21 16.2 16.4 14 12 14z"/></svg>
          @endif
        </div>
        @if (trim((string) ($d['director_name'] ?? '')) !== '')
        <div class="director-floating-badge">
          <h4>{{ $d['director_name'] }}</h4>
          @if ($t('director_title') !== '')<span>{{ $t('director_title') }}</span>@endif
        </div>
        @endif
      </div>
      @endif

      <div class="overview-text">
        @if ($t('overview_title') !== '')<h2>{{ $t('overview_title') }}</h2>@endif
        @foreach ($paras as $p)
          <p>{!! $rich($p) !!}</p>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- VISI & MISI --}}
  @if ($vision !== '' || $mission)
  <section class="visimisi-section">
    <div class="wrap">
      <div class="vm-grid {{ ($vision !== '' && $mission) ? '' : 'single' }}">
        @if ($vision !== '')
        <div class="vm-box visi">
          <h3>{{ $t('vision_heading') }}</h3>
          <p>"{!! $rich($vision) !!}"</p>
        </div>
        @endif
        @if ($mission)
        <div class="vm-box">
          <h3>{{ $t('mission_heading') }}</h3>
          <ul>
            @foreach ($mission as $m)
              <li>{!! $rich($m) !!}</li>
            @endforeach
          </ul>
        </div>
        @endif
      </div>
    </div>
  </section>
  @endif

  {{-- FILOSOFI --}}
  @if ($phil->isNotEmpty())
  <section>
    <div class="wrap">
      <div class="sec-head">
        <h2>{{ $t('philosophy_title') }}</h2>
        @if ($t('philosophy_sub') !== '')<p>{{ $t('philosophy_sub') }}</p>@endif
      </div>
      <div class="phil-grid" style="--n:{{ min($phil->count(), 5) }}">
        @foreach ($phil as $p)
          <div class="phil-item">
            <span class="num">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
            <p>{{ $p }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- SEJARAH --}}
  @if ($hist->isNotEmpty())
  <section class="history-section">
    <div class="wrap">
      <div class="sec-head">
        <h2>{{ $t('history_title') }}</h2>
        @if ($t('history_sub') !== '')<p>{{ $t('history_sub') }}</p>@endif
      </div>
      <div class="timeline-container">
        @foreach ($hist as $h)
          <div class="t-item">
            @if ($h['y'] !== '')<div class="t-year">{{ $h['y'] }}</div>@endif
            <div class="t-desc">{!! $rich($h['t']) !!}</div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif
</main>

@include('partials.public-footer')
@include('partials.search-overlay')
<script src="{{ asset('js/gustam-public.js') }}?v={{ @filemtime(public_path('js/gustam-public.js')) }}" defer></script>
</body>
</html>
