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
            @forelse ($news->blocks ?? [] as $b)
                @if (($b['type'] ?? 'p') === 'i')
                    @php $src = \App\Models\News::fileUrl($b['image'] ?? null); $cap = $news->localized($b['caption'] ?? []); @endphp
                    @if ($src)
                        <figure>
                            <img src="{{ $src }}" alt="{{ $cap }}" loading="lazy" onerror="this.closest('figure').remove()">
                            @if ($cap !== '')<figcaption>{{ $cap }}</figcaption>@endif
                        </figure>
                    @endif
                @else
                    @php $txt = $news->localized($b['text'] ?? []); @endphp
                    @if ($txt !== '')<p>{!! nl2br(e($txt)) !!}</p>@endif
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
