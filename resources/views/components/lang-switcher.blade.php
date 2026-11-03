@php
    $cur = app()->getLocale();
    $locales = config('site.locales');
    $short = fn ($c) => $c === 'zh' ? '中文' : strtoupper($c);
@endphp
<div class="lang-wrap" data-lang-wrap>
    <button type="button" class="lang" data-lang-btn aria-haspopup="true" aria-expanded="false" aria-label="{{ __('site.language') }}">{{ $short($cur) }} ▾</button>
    <div class="lang-menu">
        @foreach ($locales as $code => $name)
            <a href="{{ route('lang.switch', $code) }}" hreflang="{{ $code }}" class="{{ $code === $cur ? 'on' : '' }}">{{ $name }}</a>
        @endforeach
    </div>
</div>
