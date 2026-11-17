{{-- Header bersama untuk halaman Produk, Berita, Kontak. Param: $cur (products|news|contact), $brand ('img'|'logo') --}}
@php $brand = $brand ?? 'img'; $cur = $cur ?? ''; @endphp
<header id="siteHeader">
  <div class="wrap nav">
    @if ($brand === 'logo')
      <a class="logo" href="{{ url('/') }}"><span class="logo-icon"></span>NEW ARMADA</a>
    @else
      <a class="brand" href="{{ url('/') }}" aria-label="New Armada">
        <img src="{{ asset('storage/images/logo-newarmada.png') }}" alt="New Armada" width="41" height="52">
        <span class="brand-text">NEW ARMADA</span>
      </a>
    @endif
    <nav>
      <a href="{{ route('about') }}" @class(['cur' => $cur === 'about'])>{{ __('site.about_us') }}</a>
      <a href="{{ url('/') }}#produk" @class(['cur' => $cur === 'products'])>{{ __('site.products') }}</a>
      <a href="{{ url('/') }}#unit">{{ __('site.business_unit') }}</a>
      <a href="{{ route('news.index') }}" @class(['cur' => $cur === 'news'])>{{ __('site.news') }}</a>
      <a href="{{ url('/') }}#plant">{{ __('site.location') }}</a>
      <x-lang-switcher />
      <a class="btn btn-red" href="{{ url('/kontak') }}">{{ __('site.contact_us') }}</a>
    </nav>
  </div>
</header>
