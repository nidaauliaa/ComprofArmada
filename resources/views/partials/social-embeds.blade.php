@php
    // Carousel "Instagram" di Home — tampilan & transisi sama dengan prototype (company-profile/index.html).
    // Isi kartu berasal dari menu admin Instagram (link share / kode embed oEmbed: Instagram, Facebook, TikTok, YouTube).
    $igUrl = ! empty($site['social_instagram']) ? $site['social_instagram'] : 'https://instagram.com/newarmada.official';

    $cards = collect();
    try {
        $cards = \App\Models\SocialPost::query()->visible()->limit(12)->get()
            ->filter(fn ($sp) => $sp->safeEmbedUrl())
            ->map(fn ($sp) => [
                'src' => $sp->safeEmbedUrl(),
                'w' => $sp->embedWidth(),
                'h' => $sp->embedHeight(),
                'mode' => ($sp->platform === 'instagram' && $sp->kind === 'reel') ? 'crop' : 'fit',
                'title' => $sp->title ?: ucfirst($sp->platform),
            ])->values();
    } catch (\Throwable $e) {
        // tabel social_posts belum dimigrasi
    }

    // Belum ada konten di admin: tampilkan reel contoh dari prototype supaya tampilan tetap utuh.
    if ($cards->isEmpty()) {
        $cards = collect(['DfELll7SvLa', 'DfB_mr1yrc_', 'De_dstZSaaq', 'DejZHd7yW3U', 'DeqbjzdyRxo', 'DegBiAySwv-'])->map(fn ($c) => [
            'src' => 'https://www.instagram.com/reel/'.$c.'/embed/', 'w' => 400, 'h' => 760, 'mode' => 'crop', 'title' => 'Instagram New Armada',
        ]);
    }

    // Carousel looping butuh minimal 6 kartu: ulangi bila kurang.
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
      <a href="{{ $igUrl }}" target="_blank" rel="noopener"><span class="logo-icon"></span>NEW ARMADA</a>
    </div>

    <p class="ig-tagline">{{ __('site.social_tagline') }}</p>
    @if ($stats)
      <ul class="ig-stats" aria-label="Instagram">
        @foreach ($stats as $label => $value)
          <li><strong>{{ $value }}</strong><span>{{ $label }}</span></li>
        @endforeach
      </ul>
    @else
      <div style="height:22px"></div>
    @endif

    <div class="ig-carousel">
      <div class="ig-rail" id="igRail">
        @foreach ($carouselCards as $c)
          <div class="ig-card">
            <iframe class="ig-frame" src="{{ $c['src'] }}" loading="lazy" scrolling="no" allowfullscreen
                    data-mode="{{ $c['mode'] }}" data-w="{{ $c['w'] }}" data-h="{{ $c['h'] }}"
                    title="{{ $c['title'] }}"
                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
            <span class="ig-hit"></span>
          </div>
        @endforeach
      </div>
      <div class="ig-ctrl">
        <a class="btn btn-red" href="{{ $igUrl }}" target="_blank" rel="noopener">{{ __('site.follow_instagram') }}</a>
        <div class="ig-arrows">
          <button id="igPrev" type="button" aria-label="{{ __('site.prev') }}">‹</button>
          <button id="igNext" type="button" aria-label="{{ __('site.next') }}">›</button>
        </div>
      </div>
    </div>
  </div>
</section>
