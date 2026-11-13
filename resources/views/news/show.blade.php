@extends('news.layout')

@section('title', $news->tr('title') . ' – New Armada')

@section('content')
<section class="ah">
    <div class="ah-bg" @if ($news->thumbnail_url) style="background-image:url('{{ $news->thumbnail_url }}')" @endif></div>
    <div class="wrap">
        <a class="back" href="{{ route('news.index') }}">{{ __('site.back_all_news') }}</a>
        <span class="nh-tag">{{ $news->category }}</span>
        <h1>{{ $news->tr('title') }}</h1>
        <div class="nh-date">{{ $news->dateLabel() }}</div>
    </div>
</section>

<section class="art">
    <div class="wrap">
        <div class="art-body">
            @php
                // gabungkan gambar yang berurutan jadi satu kelompok (satu baris)
                $groups = [];
                foreach ($news->blocks ?? [] as $b) {
                    if (($b['type'] ?? 'p') === 'i') {
                        $src = \App\Models\News::fileUrl($b['image'] ?? null);
                        if (! $src) { continue; }
                        $img = ['src' => $src, 'cap' => $news->localized($b['caption'] ?? [])];
                        $last = count($groups) - 1;
                        if ($last >= 0 && $groups[$last]['type'] === 'imgs') {
                            $groups[$last]['items'][] = $img;
                        } else {
                            $groups[] = ['type' => 'imgs', 'items' => [$img]];
                        }
                    } else {
                        $txt = $news->localized($b['text'] ?? []);
                        if ($txt !== '') { $groups[] = ['type' => 'p', 'text' => $txt]; }
                    }
                }
            @endphp

            @forelse ($groups as $g)
                @if ($g['type'] === 'p')
                    <p>{!! nl2br(e($g['text'])) !!}</p>
                @elseif (count($g['items']) === 1)
                    @php $it = $g['items'][0]; @endphp
                    <figure>
                        <img src="{{ $it['src'] }}" alt="{{ $it['cap'] }}" loading="lazy" onerror="this.closest('figure').remove()">
                        @if ($it['cap'] !== '')<figcaption>{{ $it['cap'] }}</figcaption>@endif
                    </figure>
                @else
                    <div class="fig-row {{ count($g['items']) > 2 ? 'many' : '' }}" style="--n:{{ min(count($g['items']), 4) }}">
                        @foreach ($g['items'] as $it)
                            <figure>
                                <img src="{{ $it['src'] }}" alt="{{ $it['cap'] }}" loading="lazy" onerror="this.closest('figure').remove()">
                                @if ($it['cap'] !== '')<figcaption>{{ $it['cap'] }}</figcaption>@endif
                            </figure>
                        @endforeach
                    </div>
                @endif
            @empty
                <p>{{ __('site.news_empty') }}</p>
            @endforelse
        </div>
    </div>
</section>

@if ($others->isNotEmpty())
<section class="all-news">
    <div class="wrap">
        <div class="sec-head"><div><h2>{{ __('site.more_news') }}</h2></div>
            <a class="btn btn-red" href="{{ route('news.index') }}">{{ __('site.see_all') }}</a>
        </div>
        <div class="news-grid">
            @foreach ($others as $n)
                @include('news._card', ['n' => $n])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
