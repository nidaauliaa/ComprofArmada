{{-- Header bersama untuk halaman Produk, Berita, Kontak. Param: $cur (products|news|contact), $brand ('img'|'logo') --}}
@php $brand = $brand ?? 'img'; $cur = $cur ?? ''; @endphp
<header id="siteHeader">
  <div class="wrap nav">
    @if ($brand === 'logo')
      <a class="logo" href="{{ url('/') }}"><span class="logo-icon"></span>NEW ARMADA</a>
    @else
      <a class="brand" href="{{ url('/') }}" aria-label="New Armada">
        <img class="site-logo-horizontal" src="{{ asset('storage/images/armada-logo-horizontal.png') }}" alt="New Armada" width="200" height="28">
      </a>
    @endif
    <nav>
      <a href="{{ url('/') }}#produk" @class(['cur' => $cur === 'products'])>{{ __('site.products') }}</a>
      <a href="{{ url('/') }}#unit">{{ __('site.business_unit') }}</a>
      <a href="{{ route('news.index') }}" @class(['cur' => $cur === 'news'])>{{ __('site.news') }}</a>
      <a href="{{ url('/') }}#plant">{{ __('site.location') }}</a>
      <a href="{{ route('about') }}" @class(['cur' => $cur === 'about'])>{{ __('site.about_us') }}</a>
      <x-lang-switcher />
      <a class="btn btn-red" href="{{ url('/kontak') }}">{{ __('site.contact_us') }}</a>
    </nav>
  </div>
</header>
<script src="{{ asset('js/public-header.js') }}" defer></script>
