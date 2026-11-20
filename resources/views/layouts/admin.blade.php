<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') – New Armada</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ @filemtime(public_path('css/admin.css')) }}">
    @stack('styles')
</head>
<body>
    <a class="skip-link" href="#mainContent">Lewati ke konten</a>
    @include('layouts.partials.icons')

    <div class="toast-stack" id="adminToasts" aria-live="polite" aria-atomic="false"></div>
    @if (session('success'))
        <div id="adminFlashToast" data-message="{{ session('success') }}" data-tone="category" hidden></div>
    @endif

    <div class="ap" id="ap">
        @include('layouts.admin-sidebar')
        <button type="button" class="as-ov" id="asOverlay" aria-label="Tutup navigasi"></button>

        <div class="amw">
            <header class="at">
                <button type="button" class="b o s at-toggle" id="tg" aria-label="Buka navigasi" aria-controls="adminSidebar" aria-expanded="true">
                    <svg class="i" aria-hidden="true"><use href="#i-panel"/></svg>
                </button>
                <!-- <svg class="i" style="width:18px;height:18px"><use href="#i-{{ trim($__env->yieldContent('page_icon')) ?: 'home' }}"/></svg> -->
                <!-- <b>@yield('page_title', 'Beranda')</b> -->
                <span class="sp"></span>
                <span class="usr">
                    <svg class="i" aria-hidden="true"><use href="#i-user"/></svg>
                    <span class="at-user-name">{{ Auth::user()->name ?? 'Tim Marketing New Armada' }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="b o s" aria-label="Keluar dari panel admin">
                        <svg class="i" aria-hidden="true"><use href="#i-out"/></svg>
                        <span class="at-logout-label">Keluar</span>
                    </button>
                </form>
            </header>

            <main class="am" id="mainContent" tabindex="-1">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')

    <script>
        // Desktop: sidebar ciut. Mobile: drawer dengan overlay dan fokus yang terkendali.
        (function () {
            var ap = document.getElementById('ap');
            var sidebar = document.getElementById('adminSidebar');
            var toggle = document.getElementById('tg');
            var overlay = document.getElementById('asOverlay');
            var key = 'na-side';
            var mobileQuery = window.matchMedia('(max-width: 820px)');

            function isMobile() {
                return mobileQuery.matches;
            }

            function syncState() {
                var isOpen = isMobile() ? ap.classList.contains('mo') : !ap.classList.contains('col');

                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                toggle.setAttribute('aria-label', isOpen ? 'Tutup navigasi' : 'Buka navigasi');
                document.body.classList.toggle('admin-menu-open', isMobile() && isOpen);
            }

            function openMobileMenu() {
                ap.classList.add('mo');
                syncState();
                var current = sidebar.querySelector('[aria-current="page"]') || sidebar.querySelector('nav a');
                if (current) current.focus();
            }

            function closeMobileMenu(returnFocus) {
                ap.classList.remove('mo');
                syncState();
                if (returnFocus) toggle.focus();
            }

            try {
                if (!isMobile() && localStorage.getItem(key) === '1') ap.classList.add('col');
            } catch (error) {}

            toggle.addEventListener('click', function () {
                if (isMobile()) {
                    if (ap.classList.contains('mo')) {
                        closeMobileMenu(true);
                    } else {
                        openMobileMenu();
                    }
                    return;
                }

                ap.classList.toggle('col');
                try {
                    localStorage.setItem(key, ap.classList.contains('col') ? '1' : '0');
                } catch (error) {}
                syncState();
            });

            overlay.addEventListener('click', function () {
                closeMobileMenu(true);
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && isMobile() && ap.classList.contains('mo')) {
                    closeMobileMenu(true);
                }
            });

            function handleBreakpointChange() {
                if (!isMobile()) {
                    ap.classList.remove('mo');
                }
                syncState();
            }

            if (mobileQuery.addEventListener) {
                mobileQuery.addEventListener('change', handleBreakpointChange);
            } else {
                mobileQuery.addListener(handleBreakpointChange);
            }

            syncState();
        })();
    </script>
    <script>
        (function () {
            var switcher = document.querySelector('[data-admin-locale-switch]');
            if (!switcher) return;

            var slot = document.querySelector('[data-admin-locale-slot]');
            var buttons = [].slice.call(switcher.querySelectorAll('[data-admin-locale]'));
            var status = switcher.querySelector('[data-admin-locale-status]');
            var storageKey = switcher.getAttribute('data-storage-key') || 'na-admin-locale';
            var localeNames = {
                id: 'Bahasa Indonesia',
                en: 'Bahasa Inggris',
                zh: 'Bahasa Mandarin'
            };

            document.body.classList.add('admin-has-locale');

            function setLocale(locale, persist) {
                if (!localeNames[locale]) return;

                document.documentElement.dataset.adminLocale = locale;

                document.querySelectorAll('.lt-pane').forEach(function (pane) {
                    pane.classList.toggle('on', pane.getAttribute('data-lt-pane') === locale);
                });

                document.querySelectorAll('[data-lt-tab]').forEach(function (tab) {
                    tab.classList.toggle('on', tab.getAttribute('data-lt-tab') === locale);
                });

                document.querySelectorAll('[data-admin-locale-pane]').forEach(function (pane) {
                    pane.hidden = pane.getAttribute('data-admin-locale-pane') !== locale;
                });

                document.querySelectorAll('.seg [data-lang]').forEach(function (button) {
                    button.classList.toggle('on', button.getAttribute('data-lang') === locale);
                });

                buttons.forEach(function (button) {
                    button.setAttribute('aria-pressed', button.getAttribute('data-admin-locale') === locale ? 'true' : 'false');
                });

                if (status) status.textContent = localeNames[locale] + ' dipilih.';

                document.dispatchEvent(new CustomEvent('admin:locale', {
                    detail: { locale: locale }
                }));

                if (persist) {
                    try {
                        localStorage.setItem(storageKey, locale);
                    } catch (error) {}
                }
            }

            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    setLocale(button.getAttribute('data-admin-locale'), true);
                });
            });

            var initialLocale = 'id';
            try {
                var saved = localStorage.getItem(storageKey);
                if (saved && localeNames[saved]) initialLocale = saved;
            } catch (error) {}

            setLocale(initialLocale, false);

            var drawers = [].slice.call(document.querySelectorAll('.dr'));

            function repositionSwitcher() {
                var openDrawer = drawers.filter(function (drawer) {
                    return drawer.classList.contains('on');
                })[0];
                var drawerHost = openDrawer ? openDrawer.querySelector('[data-admin-locale-drawer-host]') : null;

                if (drawerHost) {
                    if (switcher.parentElement !== drawerHost) drawerHost.appendChild(switcher);
                    return;
                }

                if (slot && switcher.parentElement !== slot) slot.appendChild(switcher);
            }

            drawers.forEach(function (drawer) {
                new MutationObserver(repositionSwitcher).observe(drawer, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            });

            repositionSwitcher();
        })();
    </script>
    <script>
        (function () {
            var stack = document.getElementById('adminToasts');
            if (!stack) return;

            var symbols = {
                upload: '↑',
                delete: '×',
                category: '✓',
                success: '✓'
            };

            function dismiss(toast) {
                if (!toast || toast.classList.contains('is-leaving')) return;

                toast.classList.add('is-leaving');
                window.setTimeout(function () {
                    toast.remove();
                }, 180);
            }

            function show(message, tone) {
                if (!message) return;

                tone = symbols[tone] ? tone : 'success';

                var toast = document.createElement('div');
                toast.className = 'toast';
                toast.dataset.tone = tone;
                toast.setAttribute('role', tone === 'delete' ? 'alert' : 'status');

                var icon = document.createElement('span');
                icon.className = 'toast-icon';
                icon.setAttribute('aria-hidden', 'true');
                icon.textContent = symbols[tone];

                var copy = document.createElement('p');
                copy.className = 'toast-message';
                copy.textContent = message;

                var close = document.createElement('button');
                close.type = 'button';
                close.className = 'toast-close';
                close.setAttribute('aria-label', 'Tutup notifikasi');
                close.textContent = '×';
                close.addEventListener('click', function () {
                    dismiss(toast);
                });

                toast.appendChild(icon);
                toast.appendChild(copy);
                toast.appendChild(close);
                stack.appendChild(toast);

                var timeout = window.setTimeout(function () {
                    dismiss(toast);
                }, 4200);

                toast.addEventListener('mouseenter', function () {
                    window.clearTimeout(timeout);
                });
                toast.addEventListener('mouseleave', function () {
                    timeout = window.setTimeout(function () {
                        dismiss(toast);
                    }, 1800);
                });
            }

            window.AdminToast = {
                show: show,
                dismiss: dismiss
            };

            var flash = document.getElementById('adminFlashToast');
            if (flash) {
                show(flash.dataset.message, flash.dataset.tone || 'category');
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
