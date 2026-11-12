@php
    // Teks baru: memakai terjemahan kalau key-nya sudah ada di lang/*/site.php, kalau belum memakai teks bawaan.
    $tr = fn ($key, $default) => \Illuminate\Support\Facades\Lang::has('site.' . $key) ? __('site.' . $key) : $default;

    // Teks halaman Kontak: isian admin (Kontak Sales > Teks halaman Kontak) didahulukan,
    // kalau belum diisi memakai terjemahan bawaan di lang/*/site.php.
    $txt = function (string $settingKey, string $langKey) {
        $v = \App\Models\Setting::tr($settingKey);
        return $v !== '' ? $v : __('site.' . $langKey);
    };

    // Nomor -> link WhatsApp (0812... otomatis diubah jadi 62812...)
    $waLink = function (string $phone) {
        $d = preg_replace('/\D/', '', $phone);
        if (str_starts_with($d, '0')) { $d = '62' . substr($d, 1); }
        return 'https://wa.me/' . $d;
    };
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ __('site.contact_us') }} – New Armada</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blue-primary:#3b82f6;--blue-hover:#2563eb;--red-primary:#ff5733;--red-hover:#e03e1b;--white:#fff;--bg-light:#f8fafc;--ink-primary:#0f172a;--ink-muted:#64748b;--line-color:rgba(226,232,240,.8);--navy:#12274d;--hd:'Barlow Condensed','Arial Narrow',sans-serif;--bd:'Barlow',system-ui,sans-serif;box-sizing:border-box}
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:#fff;color:var(--ink-primary);font:400 16px/1.6 var(--bd);overflow-x:hidden}
a{color:inherit;text-decoration:none}
button{font-family:inherit}
.wrap{width:100%;max-width:1320px;margin:0 auto;padding:0 32px}
section{padding:90px 0}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--blue-primary);color:var(--white);padding:14px 30px;border-radius:8px;border:none;cursor:pointer;font:600 15px/1 var(--bd);transition:all .25s ease;box-shadow:0 4px 14px rgba(59,130,246,.25)}
.btn:hover{background:var(--blue-hover);transform:translateY(-2px);box-shadow:0 6px 20px rgba(59,130,246,.35)}
.btn.btn-red{background:var(--red-primary);box-shadow:0 4px 14px rgba(255,87,51,.25)}
.btn.btn-red:hover{background:var(--red-hover);box-shadow:0 6px 20px rgba(255,87,51,.35)}
a:focus-visible,button:focus-visible{outline:3px solid var(--red-primary);outline-offset:3px}

/* HEADER (transparan, teks gelap karena latar terang) */
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

/* Pastikan header tetap transparan & terang walau ada CSS lain yang menimpa */
body.contact-page header{position:absolute!important;background:transparent!important;box-shadow:none!important}
body.contact-page header .brand,
body.contact-page header .brand-text,
body.contact-page header nav a:not(.btn){color:var(--navy)!important;text-shadow:none!important}
body.contact-page header nav a:not(.btn):hover{color:var(--red-primary)!important}
body.contact-page header .brand img{filter:none!important}
body.contact-page header .lang{color:var(--navy)!important;background:#fff!important;border:1px solid rgba(18,39,77,.15)!important;text-shadow:none!important}

/* ===== HALAMAN KONTAK: satu layar, terang, rata tengah ===== */
.contact-hero{position:relative;overflow:hidden;min-height:100vh;min-height:100svh;padding:110px 0 56px;display:flex;align-items:center;
  background:linear-gradient(180deg,#f3f7ff 0%,#ffffff 100%)}
.contact-hero::before,.contact-hero::after{content:"";position:absolute;border-radius:50%;filter:blur(70px);pointer-events:none}
.contact-hero::before{width:480px;height:480px;left:-140px;top:-120px;background:rgba(59,130,246,.16)}
.contact-hero::after{width:420px;height:420px;right:-120px;bottom:-140px;background:rgba(255,87,51,.10)}
.contact-hero .wrap{position:relative;z-index:1}

.hero-center{max-width:760px;margin:0 auto;text-align:center}
.hero-center h1{font:800 clamp(40px,5.2vw,68px)/1 var(--hd);text-transform:uppercase;color:var(--navy);margin:0}
.hero-center h1::after{content:"";display:block;width:72px;height:4px;background:var(--red-primary);border-radius:2px;margin:20px auto 22px}
.hero-center .lead{margin:0 auto;color:var(--ink-muted);font-size:18px;max-width:600px}
.hero-center .lead strong{color:var(--ink-primary);font-weight:600}

/* Kartu sales WhatsApp */
.sales{text-align:left;margin:44px auto 0;background:#fff;border:1px solid rgba(18,39,77,.08);border-radius:24px;padding:32px;box-shadow:0 24px 60px rgba(18,39,77,.10)}
.sales h2{position:relative;font:800 clamp(24px,2.4vw,32px)/1 var(--hd);text-transform:uppercase;color:var(--navy);margin:0 0 8px;padding-bottom:16px;border-bottom:2px solid rgba(18,39,77,.08)}
.sales h2::after{content:"";position:absolute;left:0;bottom:-2px;width:56px;height:2px;background:var(--red-primary)}
.sales ul{list-style:none;margin:0;padding:0}
.sales li{display:grid;grid-template-columns:8em 1fr auto;gap:12px;align-items:center;padding:16px 0;border-bottom:1px dashed rgba(18,39,77,.12);font-size:16px}
.sales li:last-child{border-bottom:none;padding-bottom:0}
.sales .nm{font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--navy)}
.sales .ph{color:var(--ink-muted)}
.wa{display:inline-flex;align-items:center;gap:8px;background:#25d366;color:#fff;font:600 14px/1 var(--bd);padding:10px 16px;border-radius:999px;transition:background .25s,transform .25s;box-shadow:0 4px 12px rgba(37,211,102,.25)}
.wa:hover{background:#1ebe5b;transform:translateY(-1px)}
.wa svg{width:18px;height:18px;stroke:#fff;fill:none;stroke-width:1.8}

/* Layar pendek (laptop kecil) */
@media(max-height:760px){
  .contact-hero{padding:96px 0 28px}
  .hero-center h1{font-size:clamp(34px,4.4vw,54px)}
  .hero-center h1::after{margin:14px auto 16px}
  .sales{margin-top:28px;padding:24px 28px}
  .sales li{padding:11px 0}
}

/* HP */
@media(max-width:960px){
  .contact-hero{min-height:auto;padding:100px 0 56px}
}
@media(max-width:600px){
  .wrap{padding:0 20px}
  .sales{padding:22px}
  .sales li{grid-template-columns:1fr auto}
  .sales .nm{grid-column:1/-1}
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
</style>
<link rel="stylesheet" href="{{ asset('css/gustam-public.css') }}?v={{ @filemtime(public_path('css/gustam-public.css')) }}">
</head>
<body class="contact-page" data-wa-greeting="{{ $txt('contact_wa_greeting', 'wa_greeting') }}">

@include('partials.public-header', ['brand' => 'img', 'cur' => 'contact'])

<main>
  <section class="contact-hero">
    <div class="wrap">
      <div class="hero-center">
        <h1>{{ $txt('contact_title', 'contact_response_title') }}</h1>
        <p class="lead"><strong>{{ $txt('contact_lead', 'contact_intro_lead') }}</strong>
           {{ $txt('contact_intro', 'contact_intro_rest') }}</p>

        <div class="sales">
          <h2>{{ $txt('contact_sales_heading', 'sales_wa') }}</h2>
          <ul>
            @php $entries = $hq ? $hq->phoneEntries() : []; @endphp
            @forelse ($entries as $i => $s)
            <li>
              <span class="nm">{{ $s['name'] !== '' ? $s['name'] : 'Sales '.($i + 1) }}</span>
              <span class="ph">{{ $s['phone'] }}</span>
              <a class="wa" href="{{ $waLink($s['phone']) }}" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3z"/></svg>WhatsApp
              </a>
            </li>
            @empty
            <li style="grid-template-columns:1fr"><span class="ph">{{ __('site.no_sales') }}</span></li>
            @endforelse
          </ul>
        </div>

        @if (!empty($hq) && ($hq->email || $hq->t('working_hours')))
        <div class="sales hq">
          <h2>{{ __('site.head_office') }}</h2>
          <ul>
            @if ($hq->email)
            <li style="grid-template-columns:8em 1fr"><span class="nm">Email</span><a class="ph" href="mailto:{{ $hq->email }}">{{ $hq->email }}</a></li>
            @endif
            @if ($hq->t('working_hours'))
            <li style="grid-template-columns:8em 1fr"><span class="nm">Jam</span><span class="ph">{{ $hq->t('working_hours') }}</span></li>
            @endif
          </ul>
        </div>
        @endif
      </div>
    </div>
  </section>
</main>

@include('partials.public-footer')

<script>
// Jika datang dari halaman detail produk (?produk=...), pesan WhatsApp terisi otomatis
(function(){
  var q=new URLSearchParams(location.search).get('produk');
  if(!q)return;
  var msg=document.body.dataset.waGreeting+' '+q;
  document.querySelectorAll('a.wa').forEach(function(a){a.href=a.href.split('?')[0]+'?text='+encodeURIComponent(msg)});
})();
</script>
@include('partials.search-overlay')
<script src="{{ asset('js/gustam-public.js') }}?v={{ @filemtime(public_path('js/gustam-public.js')) }}" defer></script>
</body>
</html>
