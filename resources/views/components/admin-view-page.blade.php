@props(['href', 'page'])

<a
    href="{{ $href }}"
    class="b o admin-view-page"
    target="_blank"
    rel="noopener"
>
    <svg class="i" aria-hidden="true"><use href="#i-eye"/></svg>
    <span>Lihat halaman {{ $page }}</span>
</a>
