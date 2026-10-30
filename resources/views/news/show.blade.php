@extends('news.layout')

@section('title', $news->tr('title', 'id') . ' – New Armada')

@section('content')
<section class="ah">
    <div class="ah-bg" @if ($news->thumbnail_url) style="background-image:url('{{ $news->thumbnail_url }}')" @endif></div>
    <div class="wrap">
        <a class="back" href="{{ route('news.index') }}">← Semua Berita</a>
        <span class="nh-tag">{{ $news->category }}</span>
        <h1>{{ $news->tr('title', 'id') }}</h1>
        <div class="nh-date">{{ $news->dateLabel() }}</div>
    </div>
</section>

<section class="art">
    <div class="wrap">
        <div class="art-body">
            @forelse ($news->blocks ?? [] as $b)
                @if (($b['type'] ?? 'p') === 'i')
                    @php $src = \App\Models\News::fileUrl($b['image'] ?? null); $cap = data_get($b, 'caption.id', ''); @endphp
                    @if ($src)
                        <figure>
                            <img src="{{ $src }}" alt="{{ $cap }}" loading="lazy" onerror="this.closest('figure').remove()">
                            @if ($cap !== '')<figcaption>{{ $cap }}</figcaption>@endif
                        </figure>
                    @endif
                @else
                    @php $txt = data_get($b, 'text.id', ''); @endphp
                    @if ($txt !== '')<p>{!! nl2br(e($txt)) !!}</p>@endif
                @endif
            @empty
                <p>Isi berita belum tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

@if ($others->isNotEmpty())
<section class="all-news">
    <div class="wrap">
        <div class="sec-head"><div><h2>Berita Lainnya</h2></div>
            <a class="btn btn-red" href="{{ route('news.index') }}">Lihat Semua →</a>
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
