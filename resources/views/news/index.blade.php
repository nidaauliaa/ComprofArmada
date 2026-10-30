@extends('news.layout')

@section('title', 'Berita – New Armada')

@section('content')
@if ($latest->isEmpty())
    <section class="nh">
        <div class="wrap nh-grid" style="grid-template-columns:1fr">
            <div class="nh-main">
                <span class="nh-tag">Berita</span>
                <h1>Belum ada berita</h1>
                <p class="desc">Kabar terbaru dari New Armada akan tampil di sini.</p>
            </div>
        </div>
    </section>
@else
    @php $first = $latest->first(); @endphp

    {{-- HERO: berita pilihan, bisa diganti dengan klik daftar di kanan / Terpopuler --}}
    <section class="nh" id="nh">
        <div class="nh-bg" id="nhBg"
             @if ($first->thumbnail_url) style="background-image:url('{{ $first->thumbnail_url }}')" @endif></div>

        <div class="wrap nh-grid">
            <div class="nh-main">
                <span class="nh-tag" id="nhTag">Terbaru</span>
                <h1 id="nhTitle">{{ $first->tr('title', 'id') }}</h1>
                <p class="desc" id="nhDesc">{{ $first->summary(220) }}</p>
                <div class="nh-date" id="nhDate">{{ $first->dateLabel() }}</div>
                <a class="btn" id="nhLink" href="{{ route('news.show', $first->slug) }}">Baca Selengkapnya →</a>

                @if ($popular->isNotEmpty())
                    <div class="nh-trend">
                        <h4 class="nh-label">
                            <svg viewBox="0 0 24 24"><path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/></svg>Terpopuler
                        </h4>
                        <div class="nh-trend-row">
                            @foreach ($popular as $n)
                                <button type="button" class="t-card" data-pick="{{ $n->id }}">
                                    <span class="th">
                                        @if ($n->thumbnail_url)<img src="{{ $n->thumbnail_url }}" alt="" loading="lazy" onerror="this.remove()">@endif
                                    </span>
                                    <h5>{{ $n->tr('title', 'id') }}</h5>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="nh-side">
                <h4 class="nh-label">
                    <svg viewBox="0 0 24 24"><path d="M4 10v4l12 5V5z"/><path d="M16 9a4 4 0 0 1 0 6"/></svg>Berita Terkini
                </h4>
                <div id="nhSide">
                    @foreach ($latest as $n)
                        <button type="button" class="s-item {{ $loop->first ? 'current' : '' }}" data-pick="{{ $n->id }}">
                            <span class="th">
                                @if ($n->thumbnail_url)<img src="{{ $n->thumbnail_url }}" alt="" loading="lazy" onerror="this.remove()">@endif
                            </span>
                            <span>
                                <h5>{{ $n->tr('title', 'id') }}</h5>
                                <small>{{ $n->dateLabel() }}</small>
                            </span>
                        </button>
                    @endforeach
                </div>
            </aside>
        </div>
    </section>
@endif

{{-- SEMUA BERITA --}}
<section class="all-news" id="semua">
    <div class="wrap">
        <div class="sec-head">
            <div><h2>Semua Berita</h2></div>
            <p>Kumpulan kabar terbaru dari New Armada.</p>
        </div>

        @if ($all->isEmpty())
            <p style="color:var(--ink-muted)">Belum ada berita yang dipublikasikan.</p>
        @else
            <div class="news-grid">
                @foreach ($all as $n)
                    @include('news._card', ['n' => $n])
                @endforeach
            </div>

            @if ($all->hasPages())
                <div class="pg">
                    @if ($all->onFirstPage())
                        <span class="dis">‹ Sebelumnya</span>
                    @else
                        <a href="{{ $all->previousPageUrl() }}">‹ Sebelumnya</a>
                    @endif
                    <span class="pg-i">Halaman {{ $all->currentPage() }} dari {{ $all->lastPage() }}</span>
                    @if ($all->hasMorePages())
                        <a href="{{ $all->nextPageUrl() }}">Berikutnya ›</a>
                    @else
                        <span class="dis">Berikutnya ›</span>
                    @endif
                </div>
            @endif
        @endif
    </div>
</section>
@endsection

@push('scripts')
@if ($latest->isNotEmpty())
<script>
(function () {
  var D = @json((object) $heroItems->all()), FIRST = @json($first->id);
  var $ = function (id) { return document.getElementById(id); };

  function pick(id) {
    var n = D[id]; if (!n) return;
    var bg = $('nhBg');
    bg.style.backgroundImage = n.img ? 'url("' + n.img + '")' : 'none';
    bg.classList.remove('anim'); void bg.offsetWidth; bg.classList.add('anim');
    $('nhTag').textContent = (n.id === FIRST) ? 'Terbaru' : n.tag;
    $('nhTitle').textContent = n.title;
    $('nhDesc').textContent = n.desc;
    $('nhDate').textContent = n.date;
    $('nhLink').href = n.url;
    [].forEach.call(document.querySelectorAll('#nhSide .s-item'), function (b) {
      b.classList.toggle('current', Number(b.dataset.pick) === Number(id));
    });
    if (window.innerWidth <= 1000) $('nh').scrollIntoView({ behavior: 'smooth' });
  }

  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-pick]');
    if (b) pick(b.dataset.pick);
  });
})();
</script>
@endif
@endpush
