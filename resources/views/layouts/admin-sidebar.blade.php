@php
    $menu = [
        ['route' => 'dashboard',            'active' => 'dashboard',         'icon' => 'home',  'label' => 'Beranda'],
        ['route' => 'admin.banners.index',  'active' => 'admin.banners.*',   'icon' => 'image', 'label' => 'Banner'],
        ['route' => 'admin.products.index', 'active' => 'admin.products.*',  'icon' => 'bus',   'label' => 'Produk'],
        ['route' => 'admin.news.index',     'active' => 'admin.news.*',      'icon' => 'news',  'label' => 'Berita & Event'],
        ['route' => 'admin.plants.index',   'active' => 'admin.plants.*',    'icon' => 'pin',   'label' => 'Lokasi Plant'],
        ['route' => 'admin.social.index',   'active' => 'admin.social.*',    'icon' => 'ig',    'label' => 'Instagram'],
        ['route' => 'admin.settings.index', 'active' => 'admin.settings.*',  'icon' => 'gear',  'label' => 'Pengaturan Situs'],
        ['route' => 'admin.account.index',  'active' => 'admin.account.*',   'icon' => 'user',  'label' => 'Akun'],
    ];
@endphp
<aside>
    <div>
        <div class="al"><span class="lgm"></span><span class="lt">NEW<b>ARMADA</b></span></div>
        <nav>
            @foreach ($menu as $m)
                <a href="{{ \Illuminate\Support\Facades\Route::has($m['route']) ? route($m['route']) : '#' }}"
                   class="{{ request()->routeIs($m['active']) ? 'on' : '' }}"
                   title="{{ $m['label'] }}">
                    <svg class="i"><use href="#i-{{ $m['icon'] }}"/></svg><span>{{ $m['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>
