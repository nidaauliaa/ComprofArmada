<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ __('site.search_results') }}{{ $q !== '' ? ': '.$q : '' }} – {{ $site['site_name'] ?? 'New Armada' }}</title>
<meta name="robots" content="noindex">
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
<link rel="stylesheet" href="{{ asset('css/site.css') }}">
<style>
.sr-top{background:#12274d;color:#fff;padding:14px 0}
.sr-top .wrap{display:flex;align-items:center;justify-content:space-between;gap:12px}
.sr-top a.logo{font:800 24px 'Barlow Condensed',sans-serif;letter-spacing:.04em;color:#fff}
.sr-top .lang{color:#fff;background:rgba(255,255,255,.14);padding:6px 12px;border-radius:20px;border:1px solid rgba(255,255,255,.35);font-size:13px;font-weight:600}
.sr-form{display:flex;gap:10px;margin:26px 0 8px}
.sr-form input{flex:1;font:500 18px 'Barlow',sans-serif;padding:13px 16px;border:1px solid #cbd5e1;border-radius:10px;background:#fff}
.sr-form button{background:#ff5733;color:#fff;border:0;border-radius:10px;padding:0 26px;font:700 15px 'Barlow',sans-serif;cursor:pointer}
.sr-h{font:800 28px 'Barlow Condensed',sans-serif;text-transform:uppercase;margin:30px 0 12px}
.sr-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px}
.sr-card{display:block;background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:18px 20px;transition:.2s}
.sr-card:hover{border-color:#3b82f6;transform:translateY(-2px)}
.sr-card h3{margin:0 0 4px;font:800 22px 'Barlow Condensed',sans-serif;text-transform:uppercase}
.sr-card p,.sr-card small{margin:0;color:#64748b}
.sr-empty{padding:50px 0;text-align:center;color:#64748b}
</style>
</head>
<body>
<div class="sr-top"><div class="wrap">
  <a class="logo" href="{{ url('/') }}">NEW ARMADA</a>
  <x-lang-switcher />
</div></div>

<div class="wrap" style="padding-bottom:70px">
  <form class="sr-form" action="{{ route('search') }}" method="get" role="search">
    <input type="search" name="q" value="{{ $q }}" placeholder="{{ __('site.search_placeholder') }}" minlength="2" required autofocus>
    <button type="submit">{{ __('site.search') }}</button>
  </form>

  @if (mb_strlen($q) < 2)
    <div class="sr-empty">{{ __('site.min_chars') }}</div>
  @elseif ($products->isEmpty() && $news->isEmpty())
    <div class="sr-empty">{{ __('site.no_results') }}</div>
  @else
    @if ($products->isNotEmpty())
      <div class="sr-h">{{ __('site.products') }}</div>
      <div class="sr-list">
        @foreach ($products as $p)
          <a class="sr-card" href="{{ route('product.show', $p->slug) }}">
            <h3>{{ $p->name }}</h3>
            @if ($p->subtitle)<p>{{ $p->subtitle }}</p>@endif
          </a>
        @endforeach
      </div>
    @endif

    @if ($news->isNotEmpty())
      <div class="sr-h">{{ __('site.news') }}</div>
      <div class="sr-list">
        @foreach ($news as $n)
          <a class="sr-card" href="{{ $newsRoute ? route('news.show', $n->slug) : url('/').'#berita' }}">
            <small>{{ $n->published_at->translatedFormat('d F Y') }} · {{ $n->category }}</small>
            <h3>{{ $n->tr('title') }}</h3>
          </a>
        @endforeach
      </div>
    @endif
  @endif

  <p style="margin-top:36px"><a href="{{ url('/') }}" style="color:#2563eb;font-weight:600">← {{ __('site.back_home') }}</a></p>
</div>
<script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
