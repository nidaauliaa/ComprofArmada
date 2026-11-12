@php
    // Carousel sosial media di Home.
    $igUrl = ! empty($site['social_instagram'])
        ? $site['social_instagram']
        : 'https://instagram.com/newarmada.official';

    $cards = collect();

    try {
        $cards = \App\Models\SocialPost::query()
            ->visible()
            ->limit(12)
            ->get()
            ->filter(fn ($sp) => $sp->safeEmbedUrl())
            ->map(fn ($sp) => [
                'src' => $sp->safeEmbedUrl(),
                'platform' => $sp->platform,
                'w' => $sp->platform === 'tiktok' ? 320 : $sp->embedWidth(),
                'h' => $sp->platform === 'tiktok' ? 570 : $sp->embedHeight(),
                'mode' => ($sp->platform === 'instagram' && $sp->kind === 'reel')
                    ? 'crop'
                    : 'fit',
                'title' => $sp->title ?: ucfirst($sp->platform),
                'link' => $sp->source_url,
            ])
            ->values();
    } catch (\Throwable $e) {
        // Tabel social_posts belum dimigrasi.
    }

    // Konten contoh jika admin belum mengisi sosial media.
    if ($cards->isEmpty()) {
        $cards = collect([
            'DfELll7SvLa',
            'DfB_mr1yrc_',
            'De_dstZSaaq',
            'DejZHd7yW3U',
            'DeqbjzdyRxo',
            'DegBiAySwv-',
        ])->map(fn ($c) => [
            'src' => 'https://www.instagram.com/reel/'.$c.'/embed/',
            'platform' => 'instagram',
            'w' => 400,
            'h' => 760,
            'mode' => 'crop',
            'title' => 'Instagram New Armada',
            'link' => 'https://www.instagram.com/reel/'.$c.'/',
        ]);
    }

    // Carousel looping membutuhkan minimal 6 kartu (slot -1 sampai 4): ulangi bila kurang.
    $carouselCards = $cards;

    while ($carouselCards->count() < 6 && $cards->isNotEmpty()) {
        $carouselCards = $carouselCards->concat($cards);
    }

    $carouselCards = $carouselCards->values();

    $stats = array_filter([
        __('site.ig_posts') => $site['ig_posts'] ?? '',
        __('site.ig_followers') => $site['ig_followers'] ?? '',
        __('site.ig_following') => $site['ig_following'] ?? '',
    ]);
@endphp

<section id="sosmed" class="ig-section">
  <div class="wrap">
    <div class="ig-brand">
      <a class="brand" href="{{ $igUrl }}" target="_blank" rel="noopener" aria-label="New Armada Instagram">
        <img src="{{ asset('storage/images/logo-newarmada.png') }}" alt="New Armada" width="41" height="52">
        <span class="brand-text">NEW ARMADA</span>
      </a>
    </div>

    <p class="ig-tagline">{{ __('site.social_tagline') }}</p>

    @if ($stats)
      <ul class="ig-stats" aria-label="Instagram">
        @foreach ($stats as $label => $value)
          <li>
            <strong>{{ $value }}</strong>
            <span>{{ $label }}</span>
          </li>
        @endforeach
      </ul>
    @else
      <div style="height:22px"></div>
    @endif

    <div class="ig-carousel">
      <div class="ig-rail" id="igRail">
        @foreach ($carouselCards as $c)
          <div class="ig-card p-{{ $c['platform'] }}">
            <iframe class="ig-frame" src="{{ $c['src'] }}" loading="lazy" scrolling="no" allowfullscreen
                    data-platform="{{ $c['platform'] }}" data-mode="{{ $c['mode'] }}" data-w="{{ $c['w'] }}" data-h="{{ $c['h'] }}"
                    title="{{ $c['title'] }}"
                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
            {{-- Kartu redup di tepi: klik = geser carousel + buka link sumber (sama seperti versi tim) --}}
            <a class="ig-hit" href="{{ $c['link'] ?? $igUrl }}" target="_blank" rel="noopener" aria-label="{{ $c['title'] }}"></a>
          </div>
        @endforeach
        <button id="igPrev" class="ig-nav prev" type="button" aria-label="{{ __('site.prev') }}">‹</button>
        <button id="igNext" class="ig-nav next" type="button" aria-label="{{ __('site.next') }}">›</button>
      </div>
      <div class="ig-ctrl">
        <a class="ig-follow" href="{{ $igUrl }}" target="_blank" rel="noopener"><span>{{ __('site.follow_instagram') }}</span></a>
      </div>
    </div>
  </div>
</section>
