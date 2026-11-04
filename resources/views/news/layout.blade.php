<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>@yield('title', 'Berita – New Armada')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blue-primary:#3b82f6;--blue-hover:#2563eb;--red-primary:#ff5733;--red-hover:#e03e1b;--white:#fff;--bg-light:#f8fafc;--ink-primary:#0f172a;--ink-muted:#64748b;--line-color:rgba(226,232,240,.8);--navy:#12274d;--hd:'Barlow Condensed','Arial Narrow',sans-serif;--bd:'Barlow',system-ui,sans-serif}
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:var(--bg-light);color:var(--ink-primary);font:400 16px/1.6 var(--bd);overflow-x:hidden}
a{color:inherit;text-decoration:none}
button{font-family:inherit}
.wrap{width:100%;max-width:1320px;margin:0 auto;padding:0 32px}
section{padding:90px 0}
.sec-head{display:flex;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:40px;flex-wrap:wrap}
.sec-head h2{font:800 clamp(36px,4vw,52px)/1 var(--hd);text-transform:uppercase;color:var(--ink-primary);margin:0}
.sec-head p{margin:0;max-width:48ch;color:var(--ink-muted);font-size:16px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--blue-primary);color:var(--white);padding:14px 30px;border-radius:8px;border:none;cursor:pointer;font:600 15px/1 var(--bd);transition:all .25s ease;box-shadow:0 4px 14px rgba(59,130,246,.25)}
.btn:hover{background:var(--blue-hover);transform:translateY(-2px);box-shadow:0 6px 20px rgba(59,130,246,.35)}
.btn.btn-red{background:var(--red-primary);box-shadow:0 4px 14px rgba(255,87,51,.25)}
.btn.btn-red:hover{background:var(--red-hover);box-shadow:0 6px 20px rgba(255,87,51,.35)}
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

/* KARTU BERITA (sama dengan di halaman depan) */
.news-card{background:#fff;border-radius:14px;overflow:hidden;box-shadow:8px 8px 0 rgba(15,23,42,.14);display:flex;flex-direction:column;transition:transform .3s ease,box-shadow .3s ease}
.news-card:hover{transform:translate(-3px,-3px);box-shadow:11px 11px 0 rgba(15,23,42,.18)}
.news-date{padding:18px 24px 14px;font-size:13px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-muted)}
.news-img{display:block;position:relative;aspect-ratio:11/5;background:var(--navy);overflow:hidden}
.news-img img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;display:block}
.news-body{padding:20px 24px 22px;display:flex;flex-direction:column;flex:1}
.news-body h3{font:800 21px/1.2 var(--bd);letter-spacing:-.01em;margin:0 0 10px}
.news-body p{margin:0 0 16px;color:var(--ink-muted);font-size:14px;line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.news-more{margin-top:auto;padding-top:14px;border-top:1px solid var(--line-color);color:var(--red-primary);font-weight:600;font-size:15px;display:flex;align-items:center;gap:8px;transition:gap .25s}
.news-card:hover .news-more{gap:14px}

/* HERO HALAMAN BERITA */
.nh{position:relative;overflow:hidden;min-height:100vh;color:#fff;padding:112px 0 56px;display:flex;background:#0b1a38}
.nh::after{content:"";position:absolute;inset:0;z-index:1;pointer-events:none;background:linear-gradient(90deg,rgba(8,15,32,.86) 0%,rgba(8,15,32,.55) 46%,rgba(8,15,32,.15) 100%),linear-gradient(0deg,rgba(8,15,32,.7) 0%,transparent 45%),linear-gradient(180deg,rgba(8,15,32,.6) 0%,transparent 22%)}
.nh-bg{position:absolute;inset:0;z-index:0;background-color:var(--navy);background-position:center;background-size:cover;background-repeat:no-repeat}
.nh-bg.anim{animation:nhIn .7s ease}
@keyframes nhIn{from{opacity:0;transform:scale(1.03)}to{opacity:1;transform:none}}
.nh-grid{position:relative;z-index:2;display:grid;grid-template-columns:minmax(0,1fr) 380px;gap:48px;width:100%}
.nh-main{display:flex;flex-direction:column;min-height:calc(100vh - 168px)}
.nh-tag{align-self:flex-start;margin-top:clamp(20px,7vh,80px);background:var(--red-primary);color:#fff;padding:6px 14px;border-radius:6px;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}
.nh-main h1{font:800 clamp(34px,4.4vw,60px)/1.08 var(--bd);letter-spacing:-.02em;margin:20px 0 16px;max-width:680px}
.nh-main p.desc{margin:0 0 14px;max-width:560px;font-size:17px;line-height:1.65;color:rgba(255,255,255,.85);display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.nh-date{font-size:13px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.65);margin-bottom:24px}
.nh-main .btn{align-self:flex-start}
.nh-trend{margin-top:auto;padding-top:40px;max-width:660px}
.nh-label{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.85);margin:0 0 16px}
.nh-label svg{width:18px;height:18px;stroke:#fff;fill:none;stroke-width:1.8}
.nh-trend-row{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
.t-card{background:none;border:0;padding:0;text-align:left;color:#fff;cursor:pointer;font:inherit}
.t-card .th{aspect-ratio:16/10;width:100%}
.t-card h5{margin:10px 0 0;font:700 14px/1.35 var(--bd);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.t-card:hover .th{transform:translateY(-4px)}
.th{position:relative;display:block;background:var(--navy);overflow:hidden;border-radius:10px;transition:transform .3s ease}
.th img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block}
.nh-side{align-self:start;margin-top:clamp(20px,7vh,80px);background:rgba(8,15,32,.55);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.14);border-radius:16px;padding:22px}
.s-item{display:grid;grid-template-columns:80px 1fr;gap:16px;align-items:center;width:100%;text-align:left;background:none;border:0;border-left:3px solid transparent;color:#fff;cursor:pointer;font:inherit;padding:10px 10px 10px 8px;border-radius:10px;transition:background .25s,border-color .25s}
.s-item .th{width:80px;height:80px}
.s-item:hover{background:rgba(255,255,255,.08)}
.s-item.current{background:rgba(255,255,255,.12);border-left-color:var(--red-primary)}
.s-item h5{margin:0 0 6px;font:700 15px/1.3 var(--bd);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.s-item small{font-size:12px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.6)}

/* SEMUA BERITA */
.all-news{background:#e9eef5;padding:70px 0 90px}
.news-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:28px;padding:0 10px 10px 0}
.pg{display:flex;justify-content:center;align-items:center;gap:18px;margin-top:36px;font-weight:600}
.pg a,.pg .dis{padding:10px 18px;border-radius:8px;background:#fff;border:1.5px solid rgba(15,23,42,.15)}
.pg a:hover{background:var(--red-primary);border-color:var(--red-primary);color:#fff}
.pg .dis{opacity:.4}
.pg-i{color:var(--ink-muted);font-size:14px}

/* HALAMAN BACA BERITA */
.ah{position:relative;overflow:hidden;color:#fff;padding:140px 0 56px;min-height:56vh;display:flex;align-items:flex-end;background:#0b1a38}
.ah::after{content:"";position:absolute;inset:0;z-index:1;pointer-events:none;background:linear-gradient(0deg,rgba(8,15,32,.88) 0%,rgba(8,15,32,.45) 55%,rgba(8,15,32,.35) 100%)}
.ah-bg{position:absolute;inset:0;z-index:0;background-color:var(--navy);background-position:center;background-size:cover;background-repeat:no-repeat}
.ah .wrap{position:relative;z-index:2}
.ah .nh-tag{display:inline-block;margin-top:18px}
.ah h1{font:800 clamp(30px,4vw,54px)/1.1 var(--bd);letter-spacing:-.02em;margin:18px 0 12px;max-width:860px}
.ah .nh-date{margin-bottom:0}
.back{display:inline-block;font-weight:600;font-size:15px;color:rgba(255,255,255,.85)}
.back:hover{color:var(--red-primary)}
.art{background:#fff;padding:56px 0 70px}
.art-body{max-width:760px;margin:0 auto}
.art-body p{font-size:18px;line-height:1.85;color:#1e293b;margin:0 0 22px}
.art-body figure{
    margin:32px auto;
    width:100%;
    max-width:400px;
}
.art-body figure img{
    display:block;
    width:100%;
    height:auto;
    max-height:300px;
    object-fit:contain;
    border-radius:12px;
}
.art-body figcaption{
    font-size:14px;
    color:var(--ink-muted);
    margin-top:10px;
    text-align:center;
}
@media(max-width:1000px){
  .nh{min-height:0;padding-top:100px}
  .nh-grid{grid-template-columns:1fr;gap:32px}
  .nh-main{min-height:0}
  .nh-trend{padding-top:32px}
  .news-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media(max-width:640px){
  .wrap{padding:0 20px}
  .news-grid{grid-template-columns:1fr}
  .nh-trend-row{grid-template-columns:1fr 1fr}
  .nh-trend-row .t-card:last-child{display:none}
}

/* FOOTER */
footer{background:#0b172c;color:#94a3b8;padding:70px 0 30px}
.footer-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:40px}
footer h4{font:700 18px var(--hd);color:var(--white);margin:0 0 16px;text-transform:uppercase;letter-spacing:.05em}
footer ul{list-style:none;margin:0;padding:0;display:grid;gap:10px;font-size:15px}
footer a:hover{color:var(--red-primary)}
.footer-bottom{margin-top:50px;padding-top:24px;border-top:1px solid rgba(255,255,255,.1);font-size:14px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
@media(max-width:900px){.footer-grid{grid-template-columns:1fr 1fr}}
@media(prefers-reduced-motion:reduce){*{transition:none!important;scroll-behavior:auto!important}}
.brand{display:flex;align-items:center;gap:12px;color:#fff}
.brand img{display:block;height:52px;width:auto;filter:drop-shadow(0 2px 8px rgba(0,0,0,.35))}
.brand-text{font:800 26px/1 var(--hd);letter-spacing:.03em;text-shadow:0 1px 12px rgba(0,0,0,.35)}
@media(max-width:560px){.brand-text{display:none}}
nav a.cur::after{width:100%}
</style>
<link rel="stylesheet" href="{{ asset('css/gustam-public.css') }}?v={{ @filemtime(public_path('css/gustam-public.css')) }}">
</head>
<body>

@include('partials.public-header', ['brand' => 'img', 'cur' => 'news'])

<main>
@yield('content')
</main>

@include('partials.public-footer')

@stack('scripts')
@include('partials.search-overlay')
<script src="{{ asset('js/gustam-public.js') }}?v={{ @filemtime(public_path('js/gustam-public.js')) }}" defer></script>
</body>
</html>
