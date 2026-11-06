<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ __('site.contact_us') }} – New Armada</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
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
.brand{display:flex;align-items:center;gap:12px;color:#fff}
.brand img{display:block;height:52px;width:auto;filter:drop-shadow(0 2px 8px rgba(0,0,0,.35))}
.brand-text{font:800 26px/1 var(--hd);letter-spacing:.03em;text-shadow:0 1px 12px rgba(0,0,0,.35)}
@media(max-width:560px){.brand-text{display:none}}


/* HEADER versi halaman dalam: solid */
header{position:relative;background:#12274d}
/* HALAMAN KONTAK */
.page-hero{position:relative;overflow:hidden;background:#12274d;color:#fff;padding:56px 0 120px}
.page-hero::before{content:"KONTAK";position:absolute;left:50%;top:62%;transform:translate(-50%,-50%);font:800 clamp(120px,24vw,360px)/.85 var(--hd);color:rgba(255,255,255,.08);white-space:nowrap;pointer-events:none;user-select:none}
.page-hero .wrap{position:relative;z-index:1}
.page-hero h1{font:800 clamp(44px,6vw,76px)/1 var(--hd);text-transform:uppercase;margin:0 0 12px}
.page-hero p{margin:0;max-width:52ch;color:rgba(255,255,255,.8);font-size:18px}
.contact{background:var(--bg-light);padding:0 0 90px}
.contact-grid{position:relative;z-index:2;margin-top:-64px;display:grid;grid-template-columns:1.4fr 1fr;gap:28px;align-items:start}
.c-card{background:#fff;border-radius:14px;padding:36px;box-shadow:0 22px 40px rgba(15,23,42,.14)}
.c-card h2{font:800 clamp(28px,3vw,38px)/1 var(--hd);text-transform:uppercase;margin:0 0 22px}
.c-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.c-field{display:flex;flex-direction:column;gap:6px;margin-bottom:16px}
.c-field label{font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-muted)}
.c-field input,.c-field textarea{width:100%;border:1.5px solid rgba(15,23,42,.15);border-radius:10px;padding:14px 16px;font:500 16px var(--bd);color:var(--ink-primary);background:#fff;outline:none;transition:border-color .25s,box-shadow .25s}
.c-field textarea{min-height:150px;resize:vertical}
.c-field input:focus,.c-field textarea:focus{border-color:var(--blue-primary);box-shadow:0 0 0 3px rgba(59,130,246,.15)}
.c-note{margin:14px 0 0;font-size:14px;color:var(--ink-muted)}
.c-info{background:#12274d;color:#fff}
.c-info h2{color:#fff}
.c-item{display:flex;gap:16px;align-items:flex-start;padding:18px 0;border-top:1px solid rgba(255,255,255,.15)}
.c-item:first-of-type{border-top:0;padding-top:0}
.c-ico{flex:none;width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.15);display:grid;place-items:center}
.c-ico svg{width:20px;height:20px;stroke:#fff;fill:none;stroke-width:1.8}
.c-item small{display:block;font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.65);margin-bottom:4px}
.c-item p{margin:0;font-size:15px;line-height:1.6;color:rgba(255,255,255,.92)}
.c-item a:hover{color:var(--red-primary)}
.c-phones{display:grid;gap:4px;font-size:15px;line-height:1.6;color:rgba(255,255,255,.92)}
.c-info .btn{margin-top:8px;width:100%}
@media(max-width:900px){.contact-grid{grid-template-columns:1fr}.c-row{grid-template-columns:1fr}.c-card{padding:26px}}
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
<link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ @filemtime(public_path('css/site.css')) }}">
</head>
<body>

@include('partials.public-header', ['brand' => 'img', 'cur' => 'contact'])

@php
    // Info kontak diambil dari lokasi plant yang bertanda HQ (diatur di admin: Lokasi Plant).
    $hq        = \App\Models\Plant::headquarters();
    $mail      = $hq?->email ?: ($site['email_marketing'] ?? 'marketing@newarmada.co.id');
    $address   = $hq?->t('address') ?: ($site['address'] ?? '');
    $hours     = $hq?->t('working_hours') ?: ($site['working_hours'] ?? '');
    $phoneList = $hq ? $hq->phoneLinks() : [];
    if (empty($phoneList) && !empty($site['phone'])) {
        $phoneList = collect(explode(',', $site['phone']))
            ->map(fn ($p) => trim($p))->filter()
            ->map(fn ($p) => ['label' => $p, 'href' => 'tel:' . preg_replace('/[^0-9+]/', '', $p)])
            ->all();
    }
@endphp
<main>
  <section class="page-hero" style="padding-bottom:120px">
    <div class="wrap">
      <h1>{{ __('site.contact_us') }}</h1>
      <p>{{ __('site.contact_hero') }}</p>
    </div>
  </section>

  <section class="contact">
    <div class="wrap">
      <div class="contact-grid">
        <div class="c-card">
          <h2>{{ __('site.send_message') }}</h2>
          <form id="contactForm">
            <div class="c-row">
              <div class="c-field"><label for="cNama">{{ __('site.f_name') }}</label><input id="cNama" name="nama" type="text" required autocomplete="name"></div>
              <div class="c-field"><label for="cTelp">{{ __('site.f_phone') }}</label><input id="cTelp" name="telp" type="tel" autocomplete="tel"></div>
            </div>
            <div class="c-field"><label for="cEmail">{{ __('site.f_email') }}</label><input id="cEmail" name="email" type="email" required autocomplete="email"></div>
            <div class="c-field"><label for="cSubjek">{{ __('site.f_subject') }}</label><input id="cSubjek" name="subjek" type="text" placeholder="{{ __('site.subject_placeholder') }}"></div>
            <div class="c-field"><label for="cPesan">{{ __('site.f_message') }}</label><textarea id="cPesan" name="pesan" required></textarea></div>
            <button class="btn btn-red" type="submit">{{ __('site.send_message') }}</button>
            <p class="c-note" id="cNote">{{ __('site.send_note') }}</p>
          </form>
        </div>

        <aside class="c-card c-info">
          <h2>{{ __('site.head_office') }}</h2>

          @if ($address !== '')
          <div class="c-item">
            <span class="c-ico"><svg viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg></span>
            <div><small>{{ __('site.f_address') }}</small><p>{{ $address }}</p></div>
          </div>
          @endif

          @if (!empty($phoneList))
          <div class="c-item">
            <span class="c-ico"><svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg></span>
            <div><small>{{ __('site.f_phone') }}</small>
              <div class="c-phones">
                @foreach ($phoneList as $ph)
                <a href="{{ $ph['href'] }}">{{ $ph['label'] }}</a>
                @endforeach
              </div>
            </div>
          </div>
          @endif

          <div class="c-item">
            <span class="c-ico"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg></span>
            <div><small>Email</small><p><a href="mailto:{{ $mail }}">{{ $mail }}</a></p></div>
          </div>

          @if ($hours !== '')
          <div class="c-item">
            <span class="c-ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
            <div><small>{{ __('site.f_hours') }}</small><p>{{ $hours }}</p></div>
          </div>
          @endif

          <a class="btn" href="{{ url('/') }}#plant">{{ __('site.see_map') }}</a>
        </aside>
      </div>
    </div>
  </section>
</main>

@include('partials.public-footer')

<script>
// Isi subjek otomatis jika datang dari halaman detail produk (?produk=...)
(function(){var q=new URLSearchParams(location.search).get('produk');if(q)document.getElementById('cSubjek').value=@json(__('site.quote_prefix'))+' '+q})();
// Form kontak: membuka aplikasi email dengan isi pesan (belum ada backend)
(function(){
  var f=document.getElementById('contactForm');
  f.addEventListener('submit',function(e){
    e.preventDefault();
    var d=f.elements;
    var subject=d.subjek.value.trim()||(@json(__('site.mail_from'))+' '+d.nama.value.trim());
    var body='Nama: '+d.nama.value.trim()+'\nEmail: '+d.email.value.trim()+'\nTelepon: '+d.telp.value.trim()+'\n\n'+d.pesan.value.trim();
    window.location.href='mailto:'+@json($mail)+'?subject='+encodeURIComponent(subject)+'&body='+encodeURIComponent(body);
  });
})();
</script>
@include('partials.search-overlay')
<script src="{{ asset('js/site.js') }}?v={{ @filemtime(public_path('js/site.js')) }}" defer></script>
</body>
</html>
