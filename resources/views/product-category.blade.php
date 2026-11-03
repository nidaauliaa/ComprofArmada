<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="--c: {{ optional($category)->color_bg ?: '#12274d' }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ $category ? $category->t('name') . ' – ' : '' }}Produk – New Armada</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blue-primary:#3b82f6;--blue-hover:#2563eb;--red-primary:#ff5733;--red-hover:#e03e1b;--white:#fff;--bg-light:#f8fafc;--ink-primary:#0f172a;--ink-muted:#64748b;--line-color:rgba(226,232,240,.8);--hd:'Barlow Condensed','Arial Narrow',sans-serif;--bd:'Barlow',system-ui,sans-serif;--c:#12274d;box-sizing:border-box}
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:var(--bg-light);color:var(--ink-primary);font:400 16px/1.6 var(--bd);overflow-x:hidden}
a{color:inherit;text-decoration:none}
button{font-family:inherit}
.wrap{width:100%;max-width:1320px;margin:0 auto;padding:0 32px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--blue-primary);color:var(--white);padding:14px 30px;border-radius:8px;border:none;cursor:pointer;font:600 15px/1 var(--bd);transition:all .25s ease;box-shadow:0 4px 14px rgba(59,130,246,.25)}
.btn:hover{background:var(--blue-hover);transform:translateY(-2px)}
.btn.btn-red{background:var(--red-primary);box-shadow:0 4px 14px rgba(255,87,51,.25)}
.btn.btn-red:hover{background:var(--red-hover)}
.btn.ghost{background:transparent;color:var(--ink-primary);border:1.5px solid var(--line-color);box-shadow:none}
.btn.ghost:hover{background:var(--bg-light);transform:none}
a:focus-visible,button:focus-visible{outline:3px solid var(--red-primary);outline-offset:3px}

/* HEADER */
header{position:absolute;top:0;left:0;right:0;z-index:100;background:transparent}
.nav{display:flex;align-items:center;justify-content:space-between;height:76px}
.logo{font:800 26px/1 var(--hd);letter-spacing:.03em;display:flex;align-items:center;gap:10px;color:#fff;text-shadow:0 1px 12px rgba(0,0,0,.35)}
.logo-icon{width:14px;height:18px;background:var(--red-primary);transform:skewX(-18deg);border-radius:2px}
nav{display:flex;gap:32px;align-items:center;font-weight:600;font-size:15px}
nav a:not(.btn){position:relative;color:#fff;text-shadow:0 1px 12px rgba(0,0,0,.4);padding:6px 0;transition:color .25s ease}
nav a:not(.btn)::after{content:"";position:absolute;bottom:0;left:0;width:0;height:2px;background:var(--red-primary);transition:width .3s ease}
nav a:not(.btn):hover{color:var(--red-primary)}
nav a:not(.btn):hover::after,nav a.cur::after{width:100%}
.lang{font-size:13px;font-weight:600;color:#fff;background:rgba(255,255,255,.14);padding:6px 12px;border-radius:20px;border:1px solid rgba(255,255,255,.35)}
@media(max-width:900px){nav a:not(.btn):not(.lang){display:none}}
.brand{display:flex;align-items:center;gap:12px;color:#fff}
.brand img{display:block;height:52px;width:auto;filter:drop-shadow(0 2px 8px rgba(0,0,0,.35))}
.brand-text{font:800 26px/1 var(--hd);letter-spacing:.03em;text-shadow:0 1px 12px rgba(0,0,0,.35)}
@media(max-width:560px){.brand-text{display:none}}

/* BANNER KATEGORI */
.pb{position:relative;min-height:max(64vh,520px);display:flex;align-items:center;color:#fff;background:var(--c);transition:background-color .5s;overflow:hidden}
.pb::before{content:"";position:absolute;inset:0;background:repeating-linear-gradient(135deg,rgba(255,255,255,.05) 0 2px,transparent 2px 24px),linear-gradient(to top,rgba(8,15,32,.35),transparent 55%)}
.pb-word{position:absolute;left:50%;top:48%;transform:translate(-50%,-50%);font:800 clamp(110px,21vw,300px)/.85 var(--hd);color:rgba(255,255,255,.14);white-space:nowrap;pointer-events:none;user-select:none;text-transform:uppercase}
.pb .wrap{position:relative;z-index:2;display:grid;grid-template-columns:1fr 1.1fr;align-items:center;gap:40px;padding-top:110px;padding-bottom:84px}
.pb-kicker{font-size:13px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.8)}
.pb h1{font:800 clamp(44px,6vw,84px)/.95 var(--hd);text-transform:uppercase;margin:10px 0 18px}
.pb-desc{max-width:46ch;color:rgba(255,255,255,.9);font-size:18px;margin:0}
.pb-img{display:flex;justify-content:center}
.pb-img img{width:100%;max-width:640px;height:auto;filter:drop-shadow(0 30px 34px rgba(0,0,0,.4));transition:opacity .3s ease,transform .5s ease}
.pb-img img.swap{opacity:0;transform:translateX(28px)}
.pb-text>*{transition:opacity .3s ease}
.pb-text.swap>*{opacity:0}
@media(max-width:900px){.pb .wrap{grid-template-columns:1fr;padding-top:100px;padding-bottom:70px}.pb-img img{max-width:420px}}

/* BREADCRUMB + TAB */
.crumb-wrap{position:relative;z-index:5;display:flex;justify-content:center;margin-top:-26px}
.crumb{display:flex;align-items:center;gap:12px;background:#fff;border-radius:999px;padding:14px 28px;box-shadow:0 10px 30px rgba(15,23,42,.12);font-size:15px;font-weight:500}
.crumb a:hover{color:var(--red-primary)}
.crumb svg{width:20px;height:20px;stroke:var(--red-primary);fill:none;stroke-width:1.8;display:block}
.crumb .sep{color:var(--ink-muted)}
.tabs{background:#fff;box-shadow:0 6px 20px rgba(15,23,42,.05);margin-top:32px}
.tabs-in{display:flex;justify-content:center;gap:6px;overflow-x:auto;scrollbar-width:none}
.tabs-in::-webkit-scrollbar{display:none}
.tab{position:relative;background:none;border:0;padding:24px 22px;font:600 15px var(--bd);letter-spacing:.05em;text-transform:uppercase;color:#9aa3b2;cursor:pointer;white-space:nowrap;transition:color .25s}
.tab:hover{color:var(--ink-primary)}
.tab::after{content:"";position:absolute;left:22px;right:22px;bottom:0;height:3px;background:var(--c);transform:scaleX(0);transition:transform .3s ease}
.tab[aria-selected="true"]{color:var(--ink-primary)}
.tab[aria-selected="true"]::after{transform:scaleX(1)}

/* MODEL */
.models{padding:72px 0 110px}
.models-head{text-align:center;margin-bottom:48px}
.models-head h2{font:800 clamp(34px,4vw,52px)/1 var(--hd);text-transform:uppercase;margin:0 0 10px}
.models-head p{margin:0;color:var(--ink-muted)}
.mgrid{display:grid;grid-template-columns:repeat(4,1fr);gap:24px}
.mcard{background:#eceff4;border-radius:10px;padding:28px 26px 30px;display:flex;flex-direction:column;text-align:left;border:0;font:inherit;color:inherit;cursor:pointer;transition:transform .35s ease,background-color .35s ease,color .35s ease,box-shadow .35s ease;animation:rise .5s ease both;animation-delay:calc(var(--i,0)*70ms)}
.mcard:hover,.mcard:focus-visible{background:var(--c);color:#fff;transform:translateY(-6px);box-shadow:0 24px 44px rgba(15,23,42,.2)}
.mimg{aspect-ratio:4/3;display:grid;place-items:center;margin-bottom:22px}
.mimg img{max-width:100%;max-height:100%;object-fit:contain;filter:drop-shadow(0 16px 16px rgba(0,0,0,.25))}
.mcard h3{font:700 21px/1.2 var(--bd);margin:0}
.mspec{font-size:14px;opacity:.7;margin:6px 0 22px}
.mmore{margin-top:auto;align-self:flex-start;font-weight:600;font-size:15px;padding-bottom:4px;border-bottom:2px solid var(--red-primary);transition:border-color .35s}
.mcard:hover .mmore,.mcard:focus-visible .mmore{border-color:#fff}
@keyframes rise{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:none}}
@media(max-width:1100px){.mgrid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.mgrid{grid-template-columns:1fr}.wrap{padding:0 20px}}

/* FOOTER */
footer{background:#0b172c;color:#94a3b8;padding:70px 0 30px}
.footer-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:40px}
footer h4{font:700 18px var(--hd);color:var(--white);margin:0 0 16px;text-transform:uppercase;letter-spacing:.05em}
footer ul{list-style:none;margin:0;padding:0;display:grid;gap:10px;font-size:15px}
footer a:hover{color:var(--red-primary)}
.footer-bottom{margin-top:50px;padding-top:24px;border-top:1px solid rgba(255,255,255,.1);font-size:14px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
@media(max-width:900px){.footer-grid{grid-template-columns:1fr 1fr}}
@media(prefers-reduced-motion:reduce){*{transition:none!important;animation:none!important;scroll-behavior:auto!important}}
</style>
<link rel="stylesheet" href="{{ asset('css/gustam-public.css') }}?v={{ @filemtime(public_path('css/gustam-public.css')) }}">
</head>
<body>

@include('partials.public-header', ['brand' => 'img', 'cur' => 'products'])

<main>
  @if ($category)
  <section class="pb" id="banner">
    <span class="pb-word" aria-hidden="true">{{ \Illuminate\Support\Str::upper($category->t('name')) }}</span>
    <div class="wrap">
      <div class="pb-text">
        <span class="pb-kicker">{{ __('site.category') }}</span>
        <h1>{{ $category->t('name') }}</h1>
      </div>
      @php $heroImg = $category->posterPath() ?: optional($models->first())->image_path; @endphp
      <div class="pb-img">
        @if ($heroImg)
          <img src="{{ asset('storage/' . $heroImg) }}" alt="{{ $category->t('name') }}">
        @endif
      </div>
    </div>
  </section>

  <div class="crumb-wrap">
    <div class="crumb" role="navigation" aria-label="Breadcrumb">
      <a href="{{ url('/') }}" aria-label="{{ __('site.home') }}"><svg viewBox="0 0 24 24"><path d="M3 11l9-7 9 7"/><path d="M5 10v10h5v-6h4v6h5V10"/></svg></a>
      <span class="sep">›</span>
      <a href="{{ url('/') }}#produk">{{ __('site.products') }}</a>
      <span class="sep">›</span>
      <span>{{ $category->t('name') }}</span>
    </div>
  </div>

  <div class="tabs"><div class="wrap"><div class="tabs-in" role="tablist" aria-label="{{ __('site.categories') }}">
    @foreach ($categories as $c)
      <a class="tab" role="tab" href="{{ route('product.index', ['kategori' => $c->slug]) }}" aria-selected="{{ $c->id === $category->id ? 'true' : 'false' }}">{{ $c->t('name') }}</a>
    @endforeach
  </div></div></div>

  <section class="models">
    <div class="wrap">
      <div class="models-head">
        <h2>{{ __('site.models_of', ['name' => $category->t('name')]) }}</h2>
        <p>{{ __('site.models_hint') }}</p>
      </div>
      <div class="mgrid">
        @forelse ($models as $m)
          @php $img = $m->image_path ?: $category->posterPath(); @endphp
          <a class="mcard" href="{{ route('product.show', $m->slug) }}" style="--i: {{ $loop->index }}">
            <div class="mimg">
              @if ($img)
                <img src="{{ asset('storage/' . $img) }}" alt="{{ $m->name }}">
              @endif
            </div>
            <h3>{{ $m->name }}</h3>
            <p class="mspec">{{ $m->t('subtitle') }}</p>
            <span class="mmore">{{ __('site.view_model') }}</span>
          </a>
        @empty
          <p style="grid-column:1/-1;text-align:center;color:var(--ink-muted)">{{ __('site.no_products') }}</p>
        @endforelse
      </div>
    </div>
  </section>
  @else
  <section class="models" style="padding-top:120px">
    <div class="wrap">
      <div class="models-head"><h2>{{ __('site.products') }}</h2><p>{{ __('site.no_categories') }}</p></div>
    </div>
  </section>
  @endif
</main>

@include('partials.public-footer')

@include('partials.search-overlay')
<script src="{{ asset('js/gustam-public.js') }}?v={{ @filemtime(public_path('js/gustam-public.js')) }}" defer></script>
</body>
</html>