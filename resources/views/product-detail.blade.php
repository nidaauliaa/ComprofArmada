<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="--c: {{ $category->color_bg ?: '#12274d' }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ $model->name }} – {{ $category->t('name') }} – New Armada</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blue-primary:#3b82f6;--blue-hover:#2563eb;--red-primary:#ff5733;--red-hover:#e03e1b;--white:#fff;--bg-light:#f8fafc;--ink-primary:#0f172a;--ink-muted:#64748b;--line-color:rgba(226,232,240,.8);--hd:'Barlow Condensed','Arial Narrow',sans-serif;--bd:'Barlow',system-ui,sans-serif;--c:#12274d;box-sizing:border-box}
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:#fff;color:var(--ink-primary);font:400 16px/1.6 var(--bd);overflow-x:hidden}
a{color:inherit;text-decoration:none}
button{font-family:inherit}
.wrap{width:100%;max-width:1320px;margin:0 auto;padding:0 32px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--blue-primary);color:var(--white);padding:14px 30px;border-radius:999px;border:none;cursor:pointer;font:600 15px/1 var(--bd);transition:all .25s ease;box-shadow:0 4px 14px rgba(59,130,246,.25)}
.btn:hover{background:var(--blue-hover);transform:translateY(-2px)}
.btn.btn-red{background:var(--red-primary);box-shadow:0 4px 14px rgba(255,87,51,.25)}
.btn.btn-red:hover{background:var(--red-hover)}
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

/* HERO */
.dh{position:relative;overflow:hidden;background:var(--c);color:#fff;text-align:center;transition:background-color .5s;padding:150px 0 100px}
.dh::before{content:"";position:absolute;inset:0;background:repeating-linear-gradient(135deg,rgba(255,255,255,.05) 0 2px,transparent 2px 24px),linear-gradient(to top,rgba(8,15,32,.35),transparent 60%)}
.dh-word{position:absolute;left:50%;top:56%;transform:translate(-50%,-50%);font:800 clamp(90px,20vw,280px)/.85 var(--hd);color:rgba(255,255,255,.1);white-space:nowrap;pointer-events:none;user-select:none;text-transform:uppercase}
.dh .wrap{position:relative;z-index:2}
.dh-kicker{font-size:14px;font-weight:700;letter-spacing:.5em;text-transform:uppercase;color:rgba(255,255,255,.85);padding-left:.5em}
.dh h1{font:800 clamp(44px,6vw,84px)/1 var(--hd);text-transform:uppercase;margin:10px 0 0}

/* BREADCRUMB + TAB */
.crumb-wrap{position:relative;z-index:5;display:flex;justify-content:center;margin-top:-26px;padding:0 20px}
.crumb{display:flex;align-items:center;gap:12px;background:#fff;border-radius:999px;padding:14px 28px;box-shadow:0 10px 30px rgba(15,23,42,.12);font-size:15px;font-weight:500;max-width:100%;overflow:hidden;white-space:nowrap}
.crumb a:hover{color:var(--red-primary)}
.crumb svg{width:20px;height:20px;stroke:var(--red-primary);fill:none;stroke-width:1.8;display:block}
.crumb .sep{color:var(--ink-muted)}
.crumb .muted{color:var(--ink-muted)}
.crumb span:last-child{overflow:hidden;text-overflow:ellipsis}
.tabs{background:#fff;box-shadow:0 6px 20px rgba(15,23,42,.05);margin-top:32px}
.tabs-in{display:flex;justify-content:center;gap:6px;overflow-x:auto;scrollbar-width:none}
.tabs-in::-webkit-scrollbar{display:none}
.tab{position:relative;background:none;border:0;padding:24px 22px;font:600 15px var(--bd);letter-spacing:.05em;text-transform:uppercase;color:#9aa3b2;transition:color .25s;white-space:nowrap}
.tab:hover{color:var(--ink-primary)}
.tab::after{content:"";position:absolute;left:22px;right:22px;bottom:0;height:3px;background:var(--c);transform:scaleX(0);transition:transform .3s ease}
.tab[aria-current="page"]{color:var(--ink-primary)}
.tab[aria-current="page"]::after{transform:scaleX(1)}

/* DETAIL */
.dt{position:relative;overflow:hidden;padding:90px 0 100px}
.dt-grid{display:grid;grid-template-columns:minmax(0,1fr) 460px minmax(0,1.25fr);gap:0 40px;align-items:center}
.dt-text h2{font:800 28px/1.1 var(--hd);text-transform:uppercase;letter-spacing:.02em;margin:0 0 18px}
.dt-text p{margin:0 0 28px;color:#334155;font-size:18px;line-height:1.6;max-width:44ch}
.spec{position:relative;z-index:2;background:#0b172c;color:#fff;padding:40px 36px 36px;box-shadow:0 30px 60px rgba(8,15,32,.25)}
.spec dl{margin:0 0 28px}
.spec .row{display:flex;justify-content:space-between;align-items:baseline;gap:20px;padding:16px 0;border-bottom:1px solid rgba(255,255,255,.28)}
.spec .row:first-child{padding-top:0}
.spec dt{font-size:13px;letter-spacing:.04em;text-transform:uppercase;color:rgba(255,255,255,.7)}
.spec dd{margin:0;font-size:14px;font-weight:600;text-transform:uppercase;text-align:right}
.spec .btn{width:100%}
.dt-visual{position:relative;min-height:520px;display:flex;align-items:center}
.dt-visual::before{content:"";position:absolute;left:4%;top:50%;width:min(680px,100%);aspect-ratio:1;transform:translateY(-50%);border-radius:50%;background:color-mix(in srgb,var(--c) 9%,#fff)}
.dt-visual img{position:relative;width:112%;max-width:none;margin-left:-6%;height:auto;filter:drop-shadow(0 28px 28px rgba(0,0,0,.28))}
@media(max-width:1180px){.dt-grid{grid-template-columns:1fr 420px;gap:48px 32px}.dt-visual{grid-column:1/-1;min-height:0;justify-content:center}.dt-visual::before{left:50%;transform:translate(-50%,-50%);width:min(560px,90%)}.dt-visual img{width:100%;max-width:640px;margin:0}}
@media(max-width:820px){.dt{padding:60px 0 70px}.dt-grid{grid-template-columns:1fr}.dt-text p{max-width:none}.spec{padding:30px 24px}}

/* GALERI */
.gal{padding:20px 0 100px;text-align:center}
.gal h2{font:700 14px var(--bd);letter-spacing:.5em;text-transform:uppercase;margin:0 0 44px;padding-left:.5em}
.gal-track{display:flex;gap:20px;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding:0 32px;scroll-padding:0 32px}
.gal-track::-webkit-scrollbar{display:none}
.gal-item{flex:none;width:min(426px,78vw);aspect-ratio:426/375;border-radius:22px;overflow:hidden;background:#eceff4;scroll-snap-align:start;display:grid;place-items:center}
.gal-item img{width:100%;height:100%;object-fit:contain;padding:18px;filter:drop-shadow(0 14px 14px rgba(0,0,0,.2))}
.gal-item img.photo{object-fit:cover;padding:0;filter:none}
.gal-nav{display:flex;justify-content:center;gap:10px;margin-top:36px}
.gal-nav button{width:50px;height:50px;border-radius:50%;border:1px solid var(--ink-primary);background:#fff;color:var(--ink-primary);cursor:pointer;display:grid;place-items:center;transition:background-color .25s,color .25s}
.gal-nav button:hover:not(:disabled){background:var(--ink-primary);color:#fff}
.gal-nav button:disabled{border-color:#cbd2dc;color:#cbd2dc;cursor:default}
.gal-nav svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.8}

/* CTA */
.cta{background:#0b172c;color:#fff;padding:70px 0}
.cta-in{display:flex;align-items:center;justify-content:space-between;gap:32px;flex-wrap:wrap}
.cta h2{font:600 clamp(28px,3vw,38px)/1.15 var(--bd);margin:0 0 8px;max-width:22ch}
.cta p{margin:0;color:#94a3b8}

/* FOOTER */
footer{background:#08111f;color:#94a3b8;padding:70px 0 30px}
.footer-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:40px}
footer h4{font:700 18px var(--hd);color:var(--white);margin:0 0 16px;text-transform:uppercase;letter-spacing:.05em}
footer ul{list-style:none;margin:0;padding:0;display:grid;gap:10px;font-size:15px}
footer a:hover{color:var(--red-primary)}
.footer-bottom{margin-top:50px;padding-top:24px;border-top:1px solid rgba(255,255,255,.1);font-size:14px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
@media(max-width:900px){.footer-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.wrap{padding:0 20px}.gal-track{padding:0 20px;scroll-padding:0 20px}}
@media(prefers-reduced-motion:reduce){*{transition:none!important;scroll-behavior:auto!important}}
</style>
<link rel="stylesheet" href="{{ asset('css/gustam-public.css') }}?v={{ @filemtime(public_path('css/gustam-public.css')) }}">
</head>
<body>

@include('partials.public-header', ['brand' => 'img', 'cur' => 'products'])

<main>
  @php
    $mainImg = $model->image_path ?: $category->posterPath();
    $specs = array_filter([
        __('site.spec_brand')   => $model->brand ?: 'New Armada',
        __('site.spec_model')   => $model->name,
        __('site.spec_type')    => $model->t('subtitle'),
        __('site.spec_height')  => $model->height,
        __('site.spec_width')   => $model->width,
        __('site.spec_length')  => $model->length,
    ]);
  @endphp

  <section class="dh">
    <span class="dh-word" aria-hidden="true">{{ \Illuminate\Support\Str::upper($category->t('name')) }}</span>
    <div class="wrap">
      <div class="dh-kicker">{{ $category->t('name') }}</div>
      <h1>{{ $model->name }}</h1>
    </div>
  </section>

  <div class="crumb-wrap">
    <div class="crumb" role="navigation" aria-label="Breadcrumb">
      <a href="{{ url('/') }}" aria-label="{{ __('site.home') }}"><svg viewBox="0 0 24 24"><path d="M3 11l9-7 9 7"/><path d="M5 10v10h5v-6h4v6h5V10"/></svg></a>
      <span class="sep">›</span>
      <a class="muted" href="{{ route('product.index', ['kategori' => $category->slug]) }}">{{ $category->t('name') }}</a>
      <span class="sep">›</span>
      <span>{{ $model->name }}</span>
    </div>
  </div>

  <div class="tabs"><div class="wrap"><div class="tabs-in" aria-label="{{ __('site.categories') }}">
    @foreach ($categories as $c)
      <a class="tab" href="{{ route('product.index', ['kategori' => $c->slug]) }}" @if ($c->id === $category->id) aria-current="page" @endif>{{ $c->t('name') }}</a>
    @endforeach
  </div></div></div>

  <section class="dt">
    <div class="wrap dt-grid">
      <div class="dt-text">
        <h2>{{ $model->name }}</h2>
        @if ($model->t('description'))
          <p>{!! nl2br(e($model->t('description'))) !!}</p>
        @endif
        @if ($model->galleries->count())
          <a class="btn btn-red" href="#galeri">{{ __('site.see_more_images') }}</a>
        @endif
      </div>
      <div class="spec">
        <dl>
          @foreach ($specs as $label => $value)
            <div class="row"><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>
          @endforeach
        </dl>
        <a class="btn btn-red" href="{{ route('contact', ['produk' => $model->name]) }}">{{ __('site.request_quote') }}</a>
      </div>
      <div class="dt-visual">
        @if ($mainImg)
          <img src="{{ asset('storage/' . $mainImg) }}" alt="{{ $model->name }}">
        @endif
      </div>
    </div>
  </section>

  @if ($model->galleries->count())
  <section class="gal" id="galeri">
    <h2>{{ __('site.gallery') }}</h2>
    <div class="gal-track" id="galTrack">
      @foreach ($model->galleries as $g)
        @php $isPng = strtolower(pathinfo($g->image_path, PATHINFO_EXTENSION)) === 'png'; @endphp
        <div class="gal-item"><img class="{{ $isPng ? '' : 'photo' }}" src="{{ asset('storage/' . $g->image_path) }}" alt="{{ $model->name }} – {{ __('site.photo') }} {{ $loop->iteration }}" loading="lazy"></div>
      @endforeach
    </div>
    <div class="gal-nav">
      <button type="button" id="galPrev" aria-label="{{ __('site.prev') }}"><svg viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg></button>
      <button type="button" id="galNext" aria-label="{{ __('site.next') }}"><svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
    </div>
  </section>
  @endif

  <section class="cta">
    <div class="wrap cta-in">
      <div>
        <h2>{{ __('site.interested_in', ['name' => $model->name]) }}</h2>
        <p>{{ __('site.cta_sub') }}</p>
      </div>
      <a class="btn btn-red" href="{{ route('contact', ['produk' => $model->name]) }}">{{ __('site.contact_arrow') }}</a>
    </div>
  </section>
</main>

@include('partials.public-footer')

<script>
// Carousel galeri
(function(){
  var track=document.getElementById('galTrack');
  if(!track)return;
  var prev=document.getElementById('galPrev'),next=document.getElementById('galNext');
  function step(){var it=track.querySelector('.gal-item');return it?it.getBoundingClientRect().width+20:300}
  function sync(){
    prev.disabled=track.scrollLeft<=2;
    next.disabled=track.scrollLeft+track.clientWidth>=track.scrollWidth-2;
  }
  prev.addEventListener('click',function(){track.scrollBy({left:-step(),behavior:'smooth'})});
  next.addEventListener('click',function(){track.scrollBy({left:step(),behavior:'smooth'})});
  track.addEventListener('scroll',sync,{passive:true});
  window.addEventListener('resize',sync);
  sync();
})();
</script>
@include('partials.search-overlay')
<script src="{{ asset('js/gustam-public.js') }}?v={{ @filemtime(public_path('js/gustam-public.js')) }}" defer></script>
</body>
</html>