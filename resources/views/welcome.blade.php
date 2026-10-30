<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>New Armada – Company Profile</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
:root{--blue-primary:#3b82f6;--blue-hover:#2563eb;--red-primary:#ff5733;--red-hover:#e03e1b;--white:#fff;--bg-light:#f8fafc;--ink-primary:#0f172a;--ink-muted:#64748b;--line-color:rgba(226,232,240,.8);--hd:'Barlow Condensed','Arial Narrow',sans-serif;--bd:'Barlow',system-ui,sans-serif;box-sizing:border-box}
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
.sec-head.center{justify-content:center;text-align:center}
.sec-head.center h2{color:var(--white)}
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
nav a:not(.btn):hover::after{width:100%}
.lang{font-size:13px;font-weight:600;color:#fff;background:rgba(255,255,255,.14);padding:6px 12px;border-radius:20px;border:1px solid rgba(255,255,255,.35)}
@media(max-width:900px){nav a:not(.btn):not(.lang){display:none}}

/* WRAPPER + WARNA LATAR */
.hero-product-wrapper{position:relative;width:100%;background:#fff}
.spill{position:absolute;inset:0;z-index:1;background-color:#12274d;transition:background-color .7s cubic-bezier(.16,1,.3,1)}

/* HERO */
.hero-slider{position:relative;height:190vh;z-index:2;padding:0}
.hero-sticky{position:sticky;top:0;height:100vh;overflow:hidden}
.hero-media{--p:0;position:absolute;inset:-4px calc(var(--p)*7vw) calc(var(--p)*9vh);border-radius:0 0 calc(var(--p)*28px) calc(var(--p)*28px);overflow:hidden;background:#0f172a;box-shadow:0 30px 80px rgba(0,0,0,calc(var(--p)*.35));color:#fff}
.slide{position:absolute;inset:0;opacity:0;visibility:hidden;transition:opacity .8s ease,visibility .8s ease;display:flex;align-items:flex-end;z-index:2}
.slide.active{opacity:1;visibility:visible}
.slide-bg{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:62% center;z-index:0}
.slide-bg.alt{background:linear-gradient(135deg,#0b1a38,#0284c7 55%,#ff5733)}
.slide::after{content:"";position:absolute;inset:0;z-index:1;background:linear-gradient(90deg,rgba(8,15,32,.82) 0%,rgba(8,15,32,.5) 38%,rgba(8,15,32,0) 68%),linear-gradient(0deg,rgba(8,15,32,.55) 0%,rgba(8,15,32,0) 40%),linear-gradient(180deg,rgba(8,15,32,.55) 0%,rgba(8,15,32,0) 22%)}
.hero-cap{position:relative;z-index:3;text-align:left;padding-bottom:13vh}
.hero-text{max-width:760px}
.hero-cap .tag{display:inline-flex;align-items:center;background:rgba(255,255,255,.16);color:#fff;border:1px solid rgba(255,255,255,.35);backdrop-filter:blur(6px);padding:6px 16px;border-radius:20px;font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin-bottom:22px}
.hero-cap h1{font:800 clamp(32px,3.6vw,52px)/1.08 var(--bd);letter-spacing:-.02em;margin:0 0 20px;min-height:2.2em;color:#fff}
.hero-cap h1 .gradient-text{display:block;background:linear-gradient(135deg,#bfdbfe,#7dd3fc 50%,#ffb199);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
.hero-cap p{font-size:18px;line-height:1.6;color:rgba(255,255,255,.9);margin:0 0 30px;max-width:520px;min-height:3.4em}
.hero-ctrl{position:absolute;bottom:32px;right:40px;z-index:10;display:flex;align-items:center;gap:16px}
.hero-dots{display:flex;gap:8px}
.hero-dots button{width:32px;height:4px;border-radius:2px;background:rgba(255,255,255,.4);border:none;cursor:pointer;transition:all .3s ease;padding:0}
.hero-dots button.active{background:#fff;width:48px}
.hero-nav-btn{width:48px;height:48px;border-radius:50%;border:1px solid rgba(255,255,255,.5);background:rgba(255,255,255,.14);backdrop-filter:blur(6px);color:#fff;font-size:20px;display:grid;place-items:center;cursor:pointer;transition:all .25s ease}
.hero-nav-btn:hover{background:#fff;color:var(--ink-primary)}
@media(max-width:900px){.hero-media{inset:-4px calc(var(--p)*4vw) calc(var(--p)*6vh)}.hero-ctrl{right:20px;bottom:20px}.hero-cap{padding-bottom:16vh}}

/* KATEGORI */
.sec-product-fullscreen{position:relative;width:100%;height:100vh;min-height:680px;display:flex;flex-direction:column;padding:28px 0 0;z-index:2}
.sec-product-fullscreen .sec-head{margin-bottom:24px}
.cats-fullscreen{display:flex;width:100%;flex:1;min-height:560px}
.cat-card{position:relative;flex:1;min-width:0;display:flex;flex-direction:column;justify-content:space-between;padding:48px 32px;color:var(--white);transition:flex .5s cubic-bezier(.25,1,.3,1),box-shadow .4s ease;overflow:hidden;isolation:isolate;border-right:1px solid rgba(255,255,255,.18);cursor:pointer;z-index:1;box-shadow:0 28px 30px -22px rgba(8,15,32,.45)}
.cat-card:last-child{border-right:none}
.cat-card.active{flex:2.2;z-index:10;box-shadow:0 36px 40px -22px rgba(8,15,32,.6)}
.cat-card::before{content:"";position:absolute;inset:0;background:var(--card-bg);z-index:-2;transition:filter .4s ease}
.cat-card.active::before{filter:brightness(1.06)}
.cat-bus-poster{position:absolute;z-index:1;bottom:150px;left:50%;width:calc(100% - 4px);height:auto;transform:translateX(-50%);filter:drop-shadow(0 22px 26px rgba(0,0,0,.35));pointer-events:none}
.cat-big-text{position:absolute;bottom:120px;left:28px;font:800 clamp(48px,6vw,105px)/.85 var(--hd);color:rgba(255,255,255,.25);text-transform:uppercase;user-select:none;white-space:nowrap;z-index:0}
.cat-big-text::after{content:attr(data-short)}
.cat-card.active .cat-big-text::after{content:attr(data-full)}
.cat-top-meta{display:flex;justify-content:space-between;align-items:center;z-index:2}
.cat-brand-tag,.cat-meta{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.8)}
.cat-meta{margin-bottom:6px}
.cat-bottom-content{position:relative;z-index:2}
.cat-card h3{font:700 clamp(24px,3vw,42px)/1 var(--hd);margin:0;text-transform:uppercase;white-space:nowrap}
.cat-arrow{position:absolute;right:28px;bottom:40px;width:44px;height:44px;opacity:0;border-radius:50%;border:1.5px solid rgba(255,255,255,.4);background:rgba(255,255,255,.15);backdrop-filter:blur(4px);display:grid;place-items:center;font-size:18px;transition:all .3s ease;z-index:2}
.cat-card.active .cat-arrow{opacity:1;background:var(--red-primary);border-color:var(--red-primary);transform:rotate(45deg) scale(1.08)}
@media(max-width:900px){.cats-fullscreen{flex-direction:column;height:auto;min-height:0}.cat-card{min-height:300px;flex:none!important}.sec-product-fullscreen{height:auto;min-height:0;padding-top:60px}.cat-bus-poster{width:62%;bottom:130px}.cat-arrow{opacity:1}}

/* BISNIS UNIT */
#unit{background:#fff}
.bu-head{text-align:center;max-width:720px;margin:0 auto 64px}
.bu-head h2{font:800 clamp(40px,5vw,64px)/1 var(--hd);text-transform:uppercase;margin:0 0 14px}
.bu-head p{margin:0;color:var(--ink-muted);font-size:18px}
.bu-feature{display:grid;grid-template-columns:1fr 1.15fr;gap:64px;align-items:center;margin-bottom:104px}
.bu-feature h3{font:800 clamp(28px,3vw,40px)/1.1 var(--bd);letter-spacing:-.01em;margin:0 0 20px}
.bu-feature p{margin:0 0 28px;color:var(--ink-muted);font-size:17px;max-width:54ch}
.bu-media-wrap{position:relative}
.bu-media-wrap::before{content:"";position:absolute;inset:22px -20px -20px 22px;border:2px solid var(--red-primary);border-radius:16px;z-index:0}
.bu-media{position:relative;z-index:1;aspect-ratio:16/10;border-radius:16px;overflow:hidden;background:linear-gradient(135deg,#0b1a38,#0c4a7a 55%,#0284c7);box-shadow:0 30px 70px rgba(12,74,122,.28);display:grid;place-items:center;color:#fff}
.bu-media::before{content:"";position:absolute;inset:0;background:repeating-linear-gradient(135deg,rgba(255,255,255,.05) 0 2px,transparent 2px 22px)}
.bu-play{position:relative;width:84px;height:84px;border-radius:50%;background:rgba(255,255,255,.18);border:1.5px solid rgba(255,255,255,.7);backdrop-filter:blur(6px);display:grid;place-items:center}
.bu-play svg{width:28px;height:28px;fill:#fff;margin-left:4px}
.bu-media small{position:absolute;left:24px;bottom:20px;font-size:14px;color:rgba(255,255,255,.85)}
.bu-services-head{text-align:center;margin-bottom:40px}
.bu-services-head h3{font:800 clamp(30px,3.6vw,46px)/1 var(--hd);text-transform:uppercase;margin:0 0 10px}
.bu-services-head p{margin:0;color:var(--ink-muted)}
.svc-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.svc-card{position:relative;min-height:460px;border-radius:14px;overflow:hidden;color:#fff;padding:32px;display:flex;flex-direction:column;justify-content:flex-end;isolation:isolate;background:var(--photo,var(--g)) center/cover;transition:transform .35s ease,box-shadow .35s ease}
.svc-card:hover{transform:translateY(-6px);box-shadow:0 24px 50px rgba(15,23,42,.22)}
.svc-card::before{content:attr(data-word);position:absolute;top:26px;left:28px;font:800 clamp(52px,6vw,88px)/.85 var(--hd);color:rgba(255,255,255,.16);text-transform:uppercase;z-index:-1}
.svc-card::after{content:"";position:absolute;inset:0;z-index:-1;background:repeating-linear-gradient(135deg,rgba(255,255,255,.04) 0 2px,transparent 2px 22px),linear-gradient(to top,rgba(8,15,32,.72) 0%,rgba(8,15,32,.1) 62%)}
.svc-card svg{width:52px;height:52px;stroke:#fff;fill:none;stroke-width:1.6;margin-bottom:18px}
.svc-card h4{font:700 34px/1 var(--hd);text-transform:uppercase;margin:0 0 10px}
.svc-card p{margin:0;font-size:15px;line-height:1.6;color:rgba(255,255,255,.88);max-width:36ch}
@media(max-width:900px){.bu-feature{grid-template-columns:1fr;gap:48px;margin-bottom:72px}.bu-media-wrap::before{inset:14px -10px -14px 14px}.svc-grid{grid-template-columns:1fr}.svc-card{min-height:380px}}

/* INSTAGRAM REELS CAROUSEL (looping) */
.ig-section{position:relative;overflow:hidden;color:#fff;padding:90px 0;border:0;background:#0a7bb8}
.ig-section::before{content:"INSTAGRAM";position:absolute;left:50%;top:56%;transform:translate(-50%,-50%);font:800 clamp(120px,20vw,300px)/.85 var(--hd);color:rgba(255,255,255,.18);white-space:nowrap;pointer-events:none;user-select:none}
.ig-section .wrap{position:relative;z-index:1}
.ig-brand{display:flex;justify-content:center;margin:0 0 14px}
.ig-brand a{display:flex;align-items:center;gap:12px;font:800 clamp(36px,4vw,52px)/1 var(--hd);text-transform:uppercase;color:#fff}
.ig-brand .logo-icon{width:16px;height:24px}
.ig-tagline{text-align:center;margin:0 auto 26px;max-width:52ch;font-size:18px;color:rgba(255,255,255,.92)}
.ig-stats{list-style:none;display:flex;justify-content:center;margin:0 0 48px;padding:0}
.ig-stats li{display:flex;flex-direction:column;align-items:center;padding:0 36px;border-left:1px solid rgba(255,255,255,.3)}
.ig-stats li:first-child{border-left:0}
.ig-stats strong{font:800 clamp(30px,3.4vw,44px)/1 var(--hd)}
.ig-stats span{font-size:14px;color:rgba(255,255,255,.85)}
@media(max-width:600px){.ig-stats li{padding:0 18px}.ig-tagline{font-size:16px}}
.ig-carousel{position:relative;max-width:1000px;margin:0 auto}
.ig-rail{position:relative}
.ig-rail::before{content:"";display:block;padding-top:calc(44.444% - 13.333px)}
.ig-card{position:absolute;top:0;left:0;width:calc((100% - 30px)/4);height:100%;border-radius:14px;overflow:hidden;background:#000;display:block;isolation:isolate;box-shadow:0 22px 40px rgba(8,15,32,.35);opacity:0;pointer-events:none;transform:translateX(calc(var(--s,0)*(100% + 10px))) scale(var(--sc,1));transition:transform .7s cubic-bezier(.25,1,.3,1),opacity .7s ease,box-shadow .3s ease}
.ig-card[data-slot="-1"]{--s:-1;--sc:.92;opacity:.35;pointer-events:auto}
.ig-card[data-slot="0"]{--s:0;opacity:1;pointer-events:auto}
.ig-card[data-slot="1"]{--s:1;opacity:1;pointer-events:auto}
.ig-card[data-slot="2"]{--s:2;opacity:1;pointer-events:auto}
.ig-card[data-slot="3"]{--s:3;opacity:1;pointer-events:auto}
.ig-card[data-slot="4"]{--s:4;--sc:.92;opacity:.35;pointer-events:auto}
.ig-card:hover{box-shadow:0 28px 50px rgba(8,15,32,.5)}
.ig-card video{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease}
.ig-card:hover video{transform:scale(1.05)}
.ig-icon{position:absolute;top:10px;right:10px;width:24px;height:24px;stroke:#fff;fill:none;stroke-width:1.8;z-index:2;filter:drop-shadow(0 1px 4px rgba(0,0,0,.5));pointer-events:none}
.ig-ctrl{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:32px}
.ig-arrows{display:flex;gap:12px}
.ig-arrows button{width:44px;height:44px;border-radius:50%;border:1.5px solid rgba(255,255,255,.4);background:rgba(255,255,255,.15);backdrop-filter:blur(4px);color:#fff;font-size:20px;cursor:pointer;transition:all .3s ease}
.ig-arrows button:hover{background:var(--red-primary);border-color:var(--red-primary);transform:scale(1.08)}
@media(max-width:900px){
  .ig-rail::before{padding-top:calc(88.889% - 8.889px)}
  .ig-card{width:calc((100% - 10px)/2)}
  .ig-card[data-slot="2"]{--sc:.92;opacity:.35}
  .ig-card[data-slot="3"],.ig-card[data-slot="4"]{opacity:0;pointer-events:none}
}

/* BERITA */
.news-section{background:#e9eef5;padding:56px 0 52px}
.news-section .sec-head{margin-bottom:28px}
.news-ctrl{display:flex;align-items:center;gap:12px}
.news-all{padding:12px 22px;font-size:14px;margin-right:8px}
.news-ctrl button{width:44px;height:44px;border-radius:50%;border:1.5px solid rgba(15,23,42,.2);background:#fff;color:var(--ink-primary);font-size:20px;cursor:pointer;transition:all .3s ease}
.news-ctrl button:hover:not(:disabled){background:var(--red-primary);border-color:var(--red-primary);color:#fff}
.news-ctrl button:disabled{opacity:.35;cursor:default}
.news-track{display:flex;gap:28px;overflow-x:auto;scroll-snap-type:x mandatory;scroll-behavior:smooth;scrollbar-width:none;padding:0 14px 18px 0}
.news-track::-webkit-scrollbar{display:none}
.news-card{flex:0 0 calc((100% - 56px)/3);scroll-snap-align:start;background:#fff;border-radius:14px;overflow:hidden;box-shadow:8px 8px 0 rgba(15,23,42,.14);display:flex;flex-direction:column;transition:transform .3s ease,box-shadow .3s ease}
.news-card:hover{transform:translate(-3px,-3px);box-shadow:11px 11px 0 rgba(15,23,42,.18)}
.news-date{padding:18px 24px 14px;font-size:13px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-muted)}
.news-img{position:relative;aspect-ratio:11/5;background:var(--bg);display:grid;place-items:center;overflow:hidden}
.news-img img{width:72%;height:auto;filter:drop-shadow(0 16px 18px rgba(0,0,0,.35))}
.news-img.cover img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;display:block;filter:none}
.news-body{padding:20px 24px 22px;display:flex;flex-direction:column;flex:1}
.news-body h3{font:800 21px/1.2 var(--bd);letter-spacing:-.01em;margin:0 0 10px}
.news-body p{margin:0 0 16px;color:var(--ink-muted);font-size:14px;line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.news-more{margin-top:auto;padding-top:14px;border-top:1px solid var(--line-color);color:var(--red-primary);font-weight:600;font-size:15px;display:flex;align-items:center;gap:8px;transition:gap .25s}
.news-card:hover .news-more{gap:14px}
@media(max-width:900px){.news-card{flex-basis:85%}}

/* PLANT */
.plant-section{position:relative;overflow:hidden;background:var(--bg-light)}
.plant-section::before{content:"PLANT";position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);font:800 clamp(120px,24vw,360px)/.85 var(--hd);color:rgba(15,23,42,.05);white-space:nowrap;pointer-events:none;user-select:none}
.plant-section .wrap{position:relative;z-index:1}
.plant-layout{display:grid;grid-template-columns:1.7fr 1fr;gap:28px;align-items:stretch}
.plant-map-box{position:relative;z-index:0;isolation:isolate;border-radius:14px;overflow:hidden;min-height:540px;background:#aad3df;box-shadow:0 22px 40px rgba(15,23,42,.18)}
.plant-map{position:absolute;inset:0;width:100%;height:100%}
.plant-pin{background:none;border:0}
.plant-pin svg{display:block;filter:drop-shadow(0 4px 4px rgba(0,0,0,.35));transition:transform .25s ease}
.leaflet-popup-content{font:500 14px/1.5 var(--bd);margin:12px 16px}
.leaflet-popup-content strong{font:700 18px var(--hd);letter-spacing:.02em;text-transform:uppercase}
.plant-side{display:flex;flex-direction:column;gap:14px}
.plant-list{display:flex;flex-direction:column;gap:14px}
.plant-item{display:flex;align-items:center;gap:16px;width:100%;text-align:left;background:#fff;color:var(--ink-primary);border:0;border-radius:14px;padding:20px 22px;cursor:pointer;box-shadow:0 10px 24px rgba(15,23,42,.1);transition:box-shadow .3s ease,transform .3s ease}
.plant-item:hover{transform:translateY(-2px)}
.plant-item.active{box-shadow:0 0 0 3px var(--red-primary),0 16px 30px rgba(15,23,42,.18)}
.plant-item .pi-text{flex:1;min-width:0}
.plant-item strong{display:block;font:700 26px/1 var(--hd);text-transform:uppercase;margin-bottom:6px}
.plant-item span.pi-addr{display:block;color:var(--ink-muted);font-size:14px;line-height:1.5}
.pi-arrow{flex:none;width:44px;height:44px;border-radius:50%;display:grid;place-items:center;font-size:18px;background:rgba(15,23,42,.08);color:var(--ink-primary);transition:all .3s ease}
.plant-item.active .pi-arrow{background:var(--red-primary);color:#fff;transform:rotate(45deg) scale(1.08)}
.plant-actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:6px}
.plant-all{background:#fff;color:var(--ink-primary);border:1.5px solid rgba(15,23,42,.2);border-radius:8px;padding:14px 22px;font:600 15px/1 var(--bd);cursor:pointer;transition:all .3s ease}
.plant-all:hover{background:var(--ink-primary);border-color:var(--ink-primary);color:#fff}
@media(max-width:900px){.plant-layout{grid-template-columns:1fr}.plant-map-box{min-height:420px}}

/* FOOTER */
footer{background:#0b172c;color:#94a3b8;padding:70px 0 30px}
.footer-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:40px}
footer h4{font:700 18px var(--hd);color:var(--white);margin:0 0 16px;text-transform:uppercase;letter-spacing:.05em}
footer ul{list-style:none;margin:0;padding:0;display:grid;gap:10px;font-size:15px}
footer a:hover{color:var(--red-primary)}
.footer-bottom{margin-top:50px;padding-top:24px;border-top:1px solid rgba(255,255,255,.1);font-size:14px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
@media(max-width:900px){.footer-grid{grid-template-columns:1fr 1fr}}
@media(prefers-reduced-motion:reduce){*{transition:none!important;scroll-behavior:auto!important}}
</style>
</head>
<body>

<header id="siteHeader">
  <div class="wrap nav">
    <a class="logo" href="#"><span class="logo-icon"></span>NEW ARMADA</a>
    <nav>
      <a href="#produk">Produk</a>
      <a href="#unit">Bisnis Unit</a>
      <a href="#berita">Berita</a>
      <a href="#plant">Lokasi</a>
      <span class="lang">ID | EN</span>
      <a class="btn btn-red" href="contact.html">Hubungi Kami</a>
    </nav>
  </div>
</header>

<main>
  <div class="hero-product-wrapper">
    <div class="spill" id="spill"></div>

    <section class="hero-slider" id="heroSlider" aria-label="Hero Carousel">
        <div class="hero-sticky">
            <div class="hero-media" id="heroMedia">
                <div id="heroSlides" style="display:contents">
                    @php
                        $banners = \App\Models\Banner::where('is_active', true)->orderBy('sort_order')->get();
                    @endphp

                    @forelse($banners as $index => $banner)
                        <div class="slide {{ $index === 0 ? 'active' : '' }}">
                            @if($banner->image_path)
                                <img class="slide-bg" src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}">
                            @else
                                <div class="slide-bg alt"></div>
                            @endif
                            <div class="wrap hero-cap">
                                <div class="hero-text">
                                    <span class="tag">{{ $banner->t('tag') }}</span>
                                    <h1>
                                        {{ $banner->t('title') }}
                                        <span class="gradient-text">{{ $banner->t('headline') }}</span>
                                    </h1>
                                    <p>{{ $banner->t('description') }}</p>
                                    @if($banner->t('button_text'))
                                        <a class="btn {{ $index % 2 !== 0 ? 'btn-red' : '' }}" href="{{ $banner->button_link }}">
                                            {{ $banner->t('button_text') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Fallback kalau database banner masih kosong -->
                        <div class="slide active">
                            <div class="slide-bg alt"></div>
                            <div class="wrap hero-cap">
                                <div class="hero-text">
                                    <span class="tag">New Armada</span>
                                    <h1>Belum ada banner <span class="gradient-text">yang aktif</span></h1>
                                    <p>Silakan tambahkan banner melalui panel admin.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="hero-ctrl">
                    <div class="hero-dots" id="heroDots"></div>
                    <button class="hero-nav-btn" id="prevSlide" aria-label="Slide sebelumnya">‹</button>
                    <button class="hero-nav-btn" id="nextSlide" aria-label="Slide berikutnya">›</button>
                </div>
            </div>
        </div>
    </section>

    <section class="sec-product-fullscreen" id="produk">
    <div class="wrap">
        <div class="sec-head center"><h2>Kategori</h2></div>
    </div>

    <div class="cats-fullscreen">
        <div class="cat-card" data-href="{{ url('/produk?kategori=big-bus') }}" data-color="#12274d" style="--card-bg:#12274d">
        <div class="cat-top-meta"><span class="cat-brand-tag">New Armada</span></div>
        <img class="cat-bus-poster" src="images/BIGBUS-DOUBLEDECKER.png" alt="Big Bus double decker">
        <span class="cat-big-text" data-short="BIG" data-full="BIG BUS"></span>
        <div class="cat-bottom-content"><span class="cat-meta">Kategori</span><h3>Big Bus</h3></div>
        <span class="cat-arrow">→</span>
        </div>
        <div class="cat-card" data-href="{{ url('/produk?kategori=medium-bus') }}" data-color="#0c4a7a" style="--card-bg:#0c4a7a">
        <div class="cat-top-meta"><span class="cat-brand-tag">New Armada</span></div>
        <img class="cat-bus-poster" src="images/MEDIUM-BUS.png" alt="Medium bus Tourista">
        <span class="cat-big-text" data-short="MED" data-full="MEDIUM"></span>
        <div class="cat-bottom-content"><span class="cat-meta">Kategori</span><h3>Medium Bus</h3></div>
        <span class="cat-arrow">→</span>
        </div>
        <div class="cat-card" data-href="{{ url('/produk?kategori=intercity') }}" data-color="#0a7bb8" style="--card-bg:#0a7bb8">
        <div class="cat-top-meta"><span class="cat-brand-tag">New Armada</span></div>
        <img class="cat-bus-poster" src="images/INTERCITY-2.png" alt="Intercity Citouro">
        <span class="cat-big-text" data-short="INTER" data-full="INTERCITY"></span>
        <div class="cat-bottom-content"><span class="cat-meta">Kategori</span><h3>Intercity</h3></div>
        <span class="cat-arrow">→</span>
        </div>
        <div class="cat-card" data-href="{{ url('/produk?kategori=microbus') }}" data-color="#4a7fa3" style="--card-bg:#4a7fa3">
        <div class="cat-top-meta"><span class="cat-brand-tag">New Armada</span></div>
        <span class="cat-big-text" data-short="MICRO" data-full="MICROBUS"></span>
        <div class="cat-bottom-content"><span class="cat-meta">Kategori</span><h3>Microbus</h3></div>
        <span class="cat-arrow">→</span>
        </div>
        <div class="cat-card" data-href="{{ url('/produk?kategori=box-heavy-duty') }}" data-color="#b0574a" style="--card-bg:#b0574a">
        <div class="cat-top-meta"><span class="cat-brand-tag">New Armada</span></div>
        <span class="cat-big-text" data-short="HEAVY" data-full="HEAVY DUTY"></span>
        <div class="cat-bottom-content"><span class="cat-meta">Kategori</span><h3>Box &amp; Heavy Duty</h3></div>
        <span class="cat-arrow">→</span>
        </div>
    </div>
    </section>

    <script>
    // Script interaktif untuk klik card kategori
    document.querySelectorAll('.cat-card').forEach(card => {
        card.addEventListener('click', () => {
        const href = card.getAttribute('data-href');
        if (href) window.location.href = href;
        });
    });
    </script>
  </div>

  <section id="unit">
    <div class="wrap">
      <div class="bu-head">
        <h2>Bisnis Unit</h2>
        <p>Perusahaan yang bergerak di bidang karoseri sejak 1974.</p>
      </div>

      <div class="bu-feature">
        <div>
          <h3>PT. Mekar Armada Jaya Div. Stamping &amp; Tools</h3>
          <p>Menjadi produsen bodi kendaraan, komponen, dan peralatan yang memenuhi standar kualitas internasional. Menciptakan kendaraan yang aman, nyaman, dan berkualitas tinggi demi kepuasan pelanggan, serta memproduksi komponen, suku cadang, dan peralatan kelas dunia yang berdaya saing global.</p>
          <a class="btn" href="#layanan">Lihat produk dan layanan</a>
        </div>
        <div class="bu-media-wrap">
          <div class="bu-media" role="img" aria-label="Tempat video profil unit bisnis">
            <span class="bu-play"><svg viewBox="0 0 24 24"><path d="M6 4l14 8-14 8z"/></svg></span>
            <small>Video profil segera hadir</small>
          </div>
        </div>
      </div>

      <div class="bu-services-head" id="layanan">
        <h3>Produk dan Layanan</h3>
        <p>PT. Mekar Armada Jaya Div. Stamping &amp; Tools</p>
      </div>
      <div class="svc-grid">
        <article class="svc-card" data-word="Tooling" style="--g:linear-gradient(160deg,#12274d,#0a1730)">
          <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="7"/><path d="M24 6v6M24 36v6M6 24h6M36 24h6M11 11l4 4M33 33l4 4M37 11l-4 4M15 33l-4 4"/></svg>
          <h4>Tooling</h4>
          <p>Perancangan dan pembuatan dies, jig, dan fixture untuk produksi komponen bodi kendaraan.</p>
        </article>
        <article class="svc-card" data-word="Stamping" style="--g:linear-gradient(160deg,#0c4a7a,#083a62)">
          <svg viewBox="0 0 48 48"><path d="M10 8h28M24 8v14"/><rect x="14" y="22" width="20" height="6" rx="1"/><path d="M8 40h32M12 34h24"/></svg>
          <h4>Stamping</h4>
          <p>Pembentukan plat logam dengan mesin press untuk panel dan rangka bodi bus yang presisi.</p>
        </article>
        <article class="svc-card" data-word="Plating" style="--g:linear-gradient(160deg,#4a7fa3,#356585)">
          <svg viewBox="0 0 48 48"><path d="M24 6c8 10 12 16 12 22a12 12 0 0 1-24 0c0-6 4-12 12-22z"/><path d="M18 30a6 6 0 0 0 6 6"/></svg>
          <h4>Plating</h4>
          <p>Pelapisan permukaan komponen logam agar lebih tahan korosi dan tampil rapi.</p>
        </article>
      </div>

    </div>
  </section>


  <!-- BERITA -->
    <section id="berita" class="news-section">
    <div class="wrap">
        <div class="sec-head">
        <div>
            <h2>Berita</h2>
        </div>

        <div class="news-ctrl">
            <a class="btn btn-red news-all" href="{{ route('news.index') }}">
            Lihat Lainnya →
            </a>
            <button id="newsPrev" aria-label="Sebelumnya">‹</button>
            <button id="newsNext" aria-label="Berikutnya">›</button>
        </div>
        </div>

        <div class="news-track" id="newsTrack">
        @forelse ($news as $n)
            @php
            $summaryBlock = collect($n->blocks ?? [])->first(
                fn ($block) => ($block['type'] ?? 'p') === 'p'
            );

            $summary = data_get($summaryBlock, 'text.id', '');
            @endphp

            <article class="news-card">
            <div class="news-date">
                {{ $n->dateLabel() }}
            </div>

            <div class="news-img {{ $n->thumbnail_url ? 'cover' : '' }}"
                    style="--bg:#12274d">
                @if ($n->thumbnail_url)
                <img
                    src="{{ $n->thumbnail_url }}"
                    alt="{{ $n->tr('title', 'id') }}"
                    loading="lazy"
                    onerror="this.remove()"
                >
                @endif
            </div>

            <div class="news-body">
                <h3>{{ $n->tr('title', 'id') }}</h3>

                <p>
                {{ $summary ?: 'Baca berita terbaru dari New Armada.' }}
                </p>

                <a class="news-more" href="{{ route('news.show', $n->slug) }}">
                Baca Selengkapnya <span>→</span>
                </a>
            </div>
            </article>
        @empty
            <p>Belum ada berita yang dipublikasikan.</p>
        @endforelse
        </div>
    </div>
    </section>


  <!-- INSTAGRAM REELS -->
  <section id="sosmed" class="ig-section">
    <div class="wrap">
      <div class="ig-brand">
        <a href="https://instagram.com/newarmada.official" target="_blank" rel="noopener"><span class="logo-icon"></span>NEW ARMADA</a>
      </div>

      <p class="ig-tagline">Intip keseharian di pabrik, unit terbaru, dan perjalanan armada kami.</p>
      <ul class="ig-stats" aria-label="Statistik akun Instagram">
        <li><strong>2.641</strong><span>Postingan</span></li>
        <li><strong>109 rb</strong><span>Pengikut</span></li>
        <li><strong>525</strong><span>Mengikuti</span></li>
      </ul>

      <div class="ig-carousel">
        <div class="ig-rail" id="igRail">
          <a class="ig-card" href="https://instagram.com/newarmada.official" target="_blank" rel="noopener" aria-label="Buka Instagram New Armada">
            <video src="video/video-reels1.mp4" autoplay muted loop playsinline preload="metadata"></video>
            <svg class="ig-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9h18M8 3l3 6M14 3l3 6"/><path d="M10 13l4 2.5-4 2.5z"/></svg>
          </a>
          <a class="ig-card" href="https://instagram.com/newarmada.official" target="_blank" rel="noopener" aria-label="Buka Instagram New Armada">
            <video src="video/video-reels2.mp4" autoplay muted loop playsinline preload="metadata"></video>
            <svg class="ig-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9h18M8 3l3 6M14 3l3 6"/><path d="M10 13l4 2.5-4 2.5z"/></svg>
          </a>
          <a class="ig-card" href="https://instagram.com/newarmada.official" target="_blank" rel="noopener" aria-label="Buka Instagram New Armada">
            <video src="video/video-reels3.mp4" autoplay muted loop playsinline preload="metadata"></video>
            <svg class="ig-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9h18M8 3l3 6M14 3l3 6"/><path d="M10 13l4 2.5-4 2.5z"/></svg>
          </a>
          <a class="ig-card" href="https://instagram.com/newarmada.official" target="_blank" rel="noopener" aria-label="Buka Instagram New Armada">
            <video src="video/video-reels4.mp4" autoplay muted loop playsinline preload="metadata"></video>
            <svg class="ig-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9h18M8 3l3 6M14 3l3 6"/><path d="M10 13l4 2.5-4 2.5z"/></svg>
          </a>
          <a class="ig-card" href="https://instagram.com/newarmada.official" target="_blank" rel="noopener" aria-label="Buka Instagram New Armada">
            <video src="video/video-reels5.mp4" autoplay muted loop playsinline preload="metadata"></video>
            <svg class="ig-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9h18M8 3l3 6M14 3l3 6"/><path d="M10 13l4 2.5-4 2.5z"/></svg>
          </a>
          <a class="ig-card" href="https://instagram.com/newarmada.official" target="_blank" rel="noopener" aria-label="Buka Instagram New Armada">
            <video src="video/video-reels6.mp4" autoplay muted loop playsinline preload="metadata"></video>
            <svg class="ig-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9h18M8 3l3 6M14 3l3 6"/><path d="M10 13l4 2.5-4 2.5z"/></svg>
          </a>
        </div>
        <div class="ig-ctrl">
          <a class="btn btn-red" href="https://instagram.com/newarmada.official" target="_blank" rel="noopener">Follow Instagram</a>
          <div class="ig-arrows">
            <button id="igPrev" aria-label="Sebelumnya">‹</button>
            <button id="igNext" aria-label="Berikutnya">›</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  @php
    $plants = \App\Models\Plant::active()->orderBy('sort_order')->orderBy('id')->get();
  @endphp
  <section id="plant" class="plant-section">
    <div class="wrap">
      <div class="sec-head"><div><h2>Lokasi Plant</h2></div><p>Klik lokasi untuk melihat posisinya di peta.</p></div>
      <div class="plant-layout">
        <div class="plant-map-box">
          <div id="plantMap" class="plant-map" role="region" aria-label="Peta lokasi plant"></div>
        </div>
        <div class="plant-side">
          <div class="plant-list" id="plantList">
            @forelse ($plants as $p)
              <button type="button" class="plant-item">
                <span class="pi-text"><strong>{{ $p->name }}</strong><span class="pi-addr">{{ $p->address }}</span></span>
                <span class="pi-arrow">→</span>
              </button>
            @empty
              <p style="color:var(--ink-muted)">Belum ada lokasi.</p>
            @endforelse
          </div>
          @if ($plants->isNotEmpty())
            <div class="plant-actions">
              <a class="btn btn-red" id="plantGmaps" href="https://maps.google.com" target="_blank" rel="noopener">Buka di Google Maps</a>
              <button class="plant-all" id="plantAll" type="button">Lihat semua lokasi</button>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
</main>

<footer>
  <div class="wrap">
    <div class="footer-grid">
      <div><a class="logo" href="#" style="color:#fff"><span class="logo-icon"></span>NEW ARMADA</a><p style="max-width:32ch;margin-top:12px;font-size:15px">Produsen karoseri bus terkemuka untuk berbagai segmen transportasi di Indonesia.</p></div>
      <div><h4>Produk</h4><ul><li><a href="produk.html?kategori=big-bus">Big Bus</a></li><li><a href="produk.html?kategori=medium-bus">Medium Bus</a></li><li><a href="produk.html?kategori=intercity">Intercity</a></li><li><a href="produk.html?kategori=microbus">Microbus</a></li><li><a href="produk.html?kategori=box-heavy-duty">Box &amp; Heavy Duty</a></li></ul></div>
      <div><h4>Perusahaan</h4><ul><li><a href="#">Tentang Kami</a></li><li><a href="#unit">Bisnis Unit</a></li><li><a href="#plant">Lokasi Plant</a></li><li><a href="#">Karier</a></li><li><a href="{{ route('news.index') }}">Berita</a></li><li><a href="contact.html">Kontak</a></li></ul></div>
      <div><h4>Ikuti Kami</h4><ul><li><a href="https://instagram.com/newarmada.official" target="_blank" rel="noopener">Instagram</a></li><li><a href="#">YouTube</a></li><li><a href="#">LinkedIn</a></li></ul></div>
    </div>
    <div class="footer-bottom"><span>© 2026 New Armada. Hak Cipta Dilindungi.</span><span>Company Profile Redesign</span></div>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
// Peta plant (Leaflet + OpenStreetMap), data dari database
(function(){
  var el=document.getElementById('plantMap');
  var places=@json($plants->map(fn ($p) => $p->toMapArray())->values());
  if(!el||!window.L||!places.length)return;

  var map=L.map(el,{scrollWheelZoom:false});
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);

  function esc(s){return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
  function pin(color,w){
    var h=Math.round(w*32/24);
    return L.divIcon({className:'plant-pin',iconSize:[w,h],iconAnchor:[w/2,h],popupAnchor:[0,-h],
      html:'<svg viewBox="0 0 24 32" width="'+w+'" height="'+h+'"><path d="M12 0C5.4 0 0 5.4 0 12c0 9 12 20 12 20s12-11 12-20C24 5.4 18.6 0 12 0z" fill="'+color+'" stroke="#fff" stroke-width="1.5"/><circle cx="12" cy="12" r="4.5" fill="#fff"/></svg>'});
  }
  var NORMAL='#12274d',ACTIVE='#ff5733';
  var items=document.querySelectorAll('#plantList .plant-item'),markers=[];

  places.forEach(function(p,i){
    if(items[i])items[i].addEventListener('click',function(){select(i,true)});
    var m=L.marker(p.ll,{icon:pin(NORMAL,30),title:p.name}).addTo(map);
    m.bindPopup('');
    m.on('click',function(){select(i,true)});
    markers.push(m);
  });

  function select(i,fly){
    items.forEach(function(b,k){b.classList.toggle('active',k===i)});
    markers.forEach(function(m,k){
      m.setIcon(k===i?pin(ACTIVE,40):pin(NORMAL,30));
      m.setZIndexOffset(k===i?1000:0);
    });
    var p=places[i];
    markers[i].getPopup().setContent('<strong>'+esc(p.name)+'</strong><br>'+esc(p.addr)+(p.phone?'<br>Telp: '+esc(p.phone):''));
    document.getElementById('plantGmaps').href=(p.maps&&/^https?:\/\//i.test(p.maps))?p.maps:'https://www.google.com/maps/search/?api=1&query='+p.ll[0]+','+p.ll[1];
    if(fly){map.flyTo(p.ll,12,{duration:1.1});markers[i].openPopup()}
  }

  var bounds=L.latLngBounds(places.map(function(p){return p.ll}));
  function showAll(){
    map.closePopup();
    if(places.length>1)map.flyToBounds(bounds,{padding:[70,70],duration:1.1});
    else map.flyTo(places[0].ll,12,{duration:1.1});
  }
  document.getElementById('plantAll').addEventListener('click',showAll);

  // scroll-zoom hanya aktif setelah peta diklik, supaya scroll halaman nggak terganggu
  map.on('click',function(){map.scrollWheelZoom.enable()});
  el.addEventListener('mouseleave',function(){map.scrollWheelZoom.disable()});

  if(places.length>1)map.fitBounds(bounds,{padding:[70,70]});else map.setView(places[0].ll,12);
  select(0,false);
})();
</script>
<script>
// Slider hero
(function(){
  var slides=document.querySelectorAll('.slide'),box=document.getElementById('heroDots'),cur=0,timer;
  slides.forEach(function(_,i){var d=document.createElement('button');d.setAttribute('aria-label','Slide '+(i+1));d.addEventListener('click',function(){go(i)});box.appendChild(d)});
  var dots=box.querySelectorAll('button');
  function go(n){cur=(n+slides.length)%slides.length;slides.forEach(function(s,i){s.classList.toggle('active',i===cur)});dots.forEach(function(d,i){d.classList.toggle('active',i===cur)});clearInterval(timer);timer=setInterval(function(){go(cur+1)},6000)}
  document.getElementById('prevSlide').addEventListener('click',function(){go(cur-1)});
  document.getElementById('nextSlide').addEventListener('click',function(){go(cur+1)});
  go(0);
})();

// Kartu kategori aktif
(function(){
  var spill=document.getElementById('spill'),cards=document.querySelectorAll('.cat-card');
  function activate(c){cards.forEach(function(x){x.classList.toggle('active',x===c)});spill.style.backgroundColor=c.getAttribute('data-color')}
  cards.forEach(function(c){
    c.setAttribute('tabindex','0');
    c.addEventListener('mouseenter',function(){activate(c)});
    c.setAttribute('role','link');
    c.addEventListener('click',function(){location.href=c.getAttribute('data-href')});
    c.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' '){e.preventDefault();location.href=c.getAttribute('data-href')}});
    c.addEventListener('focus',function(){activate(c)});
  });
  activate(cards[0]);
})();

// Foto hero mengecil saat scroll
(function(){
  var hero=document.getElementById('heroSlider'),media=document.getElementById('heroMedia'),tick=false;
  function upd(){tick=false;var r=hero.getBoundingClientRect(),p=Math.min(1,Math.max(0,-r.top/(window.innerHeight*.8)));p=p*p*(3-2*p);media.style.setProperty('--p',p.toFixed(3))}
  window.addEventListener('scroll',function(){if(!tick){tick=true;requestAnimationFrame(upd)}},{passive:true});
  window.addEventListener('resize',upd);upd();
})();


// Carousel reels Instagram (looping, kartu pinggir fade)
(function(){
  var rail=document.getElementById('igRail');
  if(!rail)return;
  var cards=[].slice.call(rail.querySelectorAll('.ig-card')),n=cards.length,start=0,timer;
  function slotOf(i){var m=((i-start)%n+n)%n;return m===n-1?-1:m}
  function render(){
    cards.forEach(function(c,i){
      var s=slotOf(i),old=c.dataset.slot;
      if(old!==undefined&&Math.abs(s-Number(old))>1){
        c.style.transition='none';c.style.opacity='0';c.dataset.slot=s;
        void c.offsetWidth;
        c.style.transition='';c.style.opacity='';
      }else c.dataset.slot=s;
    });
  }
  function next(){start=(start+1)%n;render()}
  function prev(){start=(start-1+n)%n;render()}
  function visibleCount(){return window.innerWidth<=900?2:4}

  document.getElementById('igNext').addEventListener('click',function(){next();restart()});
  document.getElementById('igPrev').addEventListener('click',function(){prev();restart()});

  // klik kartu fade = geser; klik kartu biasa = buka Instagram
  cards.forEach(function(c){
    c.addEventListener('click',function(e){
      var s=Number(c.dataset.slot);
      if(s<0){e.preventDefault();prev();restart()}
      else if(s>=visibleCount()){e.preventDefault();next();restart()}
    });
  });

  // swipe di HP
  var x0=null;
  rail.addEventListener('touchstart',function(e){x0=e.touches[0].clientX},{passive:true});
  rail.addEventListener('touchend',function(e){
    if(x0===null)return;
    var dx=e.changedTouches[0].clientX-x0;x0=null;
    if(Math.abs(dx)>40){dx<0?next():prev();restart()}
  });

  // auto putar tiap 4,5 detik (berhenti saat di-hover)
  var reduce=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  function restart(){clearInterval(timer);if(!reduce)timer=setInterval(next,4500)}
  rail.addEventListener('mouseenter',function(){clearInterval(timer)});
  rail.addEventListener('mouseleave',restart);

  render();restart();
})();
// Carousel berita
(function(){
  var t=document.getElementById('newsTrack'),p=document.getElementById('newsPrev'),n=document.getElementById('newsNext');
  if(!t)return;
  function step(){var c=t.querySelector('.news-card');return c.offsetWidth+28}
  function upd(){p.disabled=t.scrollLeft<=2;n.disabled=t.scrollLeft+t.clientWidth>=t.scrollWidth-2}
  p.addEventListener('click',function(){t.scrollBy({left:-step()})});
  n.addEventListener('click',function(){t.scrollBy({left:step()})});
  t.addEventListener('scroll',upd,{passive:true});
  window.addEventListener('resize',upd);upd();
})();
</script>
</body>
</html>
