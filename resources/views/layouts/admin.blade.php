<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') – New Armada</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ @filemtime(public_path('css/admin.css')) }}">
    @stack('styles')
</head>
<body>
    @include('layouts.partials.icons')

    <div class="ap" id="ap">
        @include('layouts.admin-sidebar')

        <div class="amw">
            <div class="at">
                <button type="button" class="b o s" id="tg" aria-label="Buka atau tutup menu"><svg class="i"><use href="#i-panel"/></svg></button>
                <svg class="i" style="width:18px;height:18px"><use href="#i-{{ trim($__env->yieldContent('page_icon')) ?: 'home' }}"/></svg>
                <b>@yield('page_title', 'Beranda')</b>
                <span class="sp"></span>
                <span class="usr"><svg class="i"><use href="#i-user"/></svg> {{ Auth::user()->name ?? 'Tim Marketing New Armada' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="b o s"><svg class="i"><use href="#i-out"/></svg> Keluar</button>
                </form>
            </div>

            <div class="am">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    @stack('modals')

    <script>
        // Buka/tutup sidebar (desktop: ciut jadi ikon, HP: menu geser). Pilihan diingat.
        (function () {
            var ap = document.getElementById('ap'), key = 'na-side';
            try { if (innerWidth > 820 && localStorage.getItem(key) === '1') ap.classList.add('col'); } catch (e) {}
            document.getElementById('tg').addEventListener('click', function () {
                if (innerWidth <= 820) { ap.classList.toggle('mo'); return; }
                ap.classList.toggle('col');
                try { localStorage.setItem(key, ap.classList.contains('col') ? '1' : '0'); } catch (e) {}
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
