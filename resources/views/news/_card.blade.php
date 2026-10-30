<article class="news-card">
    <div class="news-date">{{ $n->dateLabel() }}</div>

    <a class="news-img" href="{{ route('news.show', $n->slug) }}" tabindex="-1" aria-hidden="true">
        @if ($n->thumbnail_url)
            <img src="{{ $n->thumbnail_url }}" alt="" loading="lazy" onerror="this.remove()">
        @endif
    </a>

    <div class="news-body">
        <h3>{{ $n->tr('title', 'id') }}</h3>
        <p>{{ $n->summary(160) ?: 'Baca berita terbaru dari New Armada.' }}</p>
        <a class="news-more" href="{{ route('news.show', $n->slug) }}">Baca Selengkapnya <span>→</span></a>
    </div>
</article>
