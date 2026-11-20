@php
    $menu = [
        ['route' => 'dashboard',            'active' => 'dashboard',         'icon' => 'home',  'label' => 'Beranda'],
        ['route' => 'admin.banners.index',  'active' => 'admin.banners.*',   'icon' => 'image', 'label' => 'Banner'],
        ['route' => 'admin.products.index', 'active' => 'admin.products.*',  'icon' => 'bus',   'label' => 'Produk'],
        ['route' => 'admin.news.index',     'active' => 'admin.news.*',      'icon' => 'news',  'label' => 'Berita & Event'],
        ['route' => 'admin.plants.index',   'active' => 'admin.plants.*',    'icon' => 'pin',   'label' => 'Lokasi Plant'],
        ['route' => 'admin.social.index',   'active' => 'admin.social.*',    'icon' => 'ig',    'label' => 'Instagram'],
        ['route' => 'admin.settings.index', 'active' => 'admin.settings.*',  'icon' => 'gear',  'label' => 'Pengaturan Situs'],
        ['route' => 'admin.sales.index',    'active' => 'admin.sales.*',     'icon' => 'phone', 'label' => 'Kontak'],
        ['route' => 'admin.about.edit',     'active' => 'admin.about.*',     'icon' => 'text',  'label' => 'Tentang Kami'],
    ];
@endphp
<aside class="as" id="adminSidebar" aria-label="Sidebar admin">
    <div class="as-inner">
        <a class="al" href="{{ route('dashboard') }}" aria-label="Beranda admin New Armada">
            <img
                src="{{ asset('storage/images/armada-logo-horizontal.png') }}"
                alt=""
                class="admin-logo"
            >
            <img
                src="{{ asset('storage/images/logo-newarmada.png') }}"
                alt=""
                class="admin-logo-mark"
            >
        </a>
        <div class="as-label">Menu utama</div>
        <nav aria-label="Navigasi utama">
            @foreach ($menu as $m)
                <a href="{{ \Illuminate\Support\Facades\Route::has($m['route']) ? route($m['route']) : '#' }}"
                   title="{{ $m['label'] }}"
                   aria-label="{{ $m['label'] }}"
                   @if (request()->routeIs($m['active'])) aria-current="page" @endif>
                    <svg class="i" aria-hidden="true"><use href="#i-{{ $m['icon'] }}"/></svg><span>{{ $m['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>
