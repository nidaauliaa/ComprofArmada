@extends('layouts.admin')

@section('title', 'Kontak')
@section('page_icon', 'phone')
@section('page_title', 'Kontak')

@push('styles')
<style>
    .sales-page {
        --sales-card:#fff;
        --sales-background:#fff;
        --sales-muted:#f9fafb;
        --sales-secondary:#f3f4f6;
        --sales-border:#e5e7eb;
        --sales-foreground:#333;
        --sales-muted-foreground:#6b7280;
        --sales-primary:#2563eb;
        --sales-primary-strong:#1d4ed8;
        --sales-accent:#e0f2fe;
        --sales-accent-foreground:#1e3a8a;
        --sales-ring:#3b82f6;
        --sales-radius:.375rem;
        color:var(--sales-foreground);
        font-family:var(--font-sans);
    }

    .sales-head {
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:24px;
        padding:24px;
        border:1px solid var(--sales-border);
        border-radius:14px;
        background:var(--sales-card);
        box-shadow:0 1px 2px rgba(15,23,42,.04);
    }

    .sales-head-copy { max-width:660px; }

    .sales-eyebrow {
        display:inline-flex;
        align-items:center;
        gap:7px;
        color:var(--sales-primary-strong);
        font-size:12px;
        font-weight:700;
        letter-spacing:.09em;
        line-height:1.3;
        text-transform:uppercase;
    }

    .sales-eyebrow .i { width:16px;height:16px; }

    .sales-head h1 {
        margin:8px 0 6px;
        color:#262626;
        font-size:clamp(1.75rem,3vw,2.25rem);
        font-weight:700;
        line-height:1.12;
        letter-spacing:-.035em;
        text-wrap:balance;
    }

    .sales-head p {
        max-width:66ch;
        margin:0;
        color:var(--sales-muted-foreground);
        font-size:14px;
        line-height:1.65;
        text-wrap:pretty;
    }

    .sales-head-actions { display:flex;align-items:center;flex:none; }

    .sales-locale-switch {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:20px;
        margin-top:14px;
        padding:13px 15px;
        border:1px solid var(--sales-border);
        border-radius:12px;
        background:var(--sales-card);
        box-shadow:0 1px 2px rgba(15,23,42,.035);
    }

    .sales-locale-copy { display:grid;gap:2px; }
    .sales-locale-copy strong { color:var(--sales-foreground);font-size:13px;font-weight:600; }
    .sales-locale-copy span { color:var(--sales-muted-foreground);font-size:12px;line-height:1.5; }

    .sales-locale-options {
        display:inline-flex;
        flex:none;
        gap:3px;
        padding:3px;
        border:1px solid var(--sales-border);
        border-radius:8px;
        background:var(--sales-secondary);
    }

    .sales-locale-button {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:54px;
        min-height:36px;
        padding:7px 12px;
        border:1px solid transparent;
        border-radius:6px;
        background:transparent;
        color:var(--sales-muted-foreground);
        font:600 12px/1 var(--font-sans);
        cursor:pointer;
        transition:background-color 150ms ease,border-color 150ms ease,color 150ms ease;
    }

    .sales-locale-button:hover { background:var(--sales-card);color:var(--sales-foreground); }

    .sales-locale-button[aria-pressed="true"] {
        border-color:#bfdbfe;
        background:var(--sales-accent);
        color:var(--sales-accent-foreground);
    }

    .sales-locale-button:focus-visible,
    .sales-btn:focus-visible,
    .sales-icon-btn:focus-visible,
    .sales-page .lt-tab:focus-visible {
        outline:2px solid var(--sales-ring);
        outline-offset:2px;
    }

    .sales-sr-only {
        position:absolute;
        width:1px;
        height:1px;
        padding:0;
        margin:-1px;
        overflow:hidden;
        clip:rect(0,0,0,0);
        white-space:nowrap;
        border:0;
    }

    .sales-alert {
        margin-top:14px;
        padding:12px 14px;
        border:1px solid #fecaca;
        border-radius:var(--sales-radius);
        background:#fef2f2;
        color:#991b1b;
        font-size:13px;
        line-height:1.55;
    }

    .sales-alert.success { border-color:#bbf7d0;background:#f0fdf4;color:#166534; }
    .sales-alert strong { display:block;margin-bottom:3px;font-weight:700; }
    .sales-alert ul { margin:0;padding-left:18px; }

    .sales-card {
        margin-top:14px;
        padding:22px;
        border:1px solid var(--sales-border);
        border-radius:14px;
        background:var(--sales-card);
        box-shadow:0 1px 2px rgba(15,23,42,.035);
    }

    .sales-card-head {
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:16px;
        margin-bottom:20px;
        padding-bottom:16px;
        border-bottom:1px solid var(--sales-border);
    }

    .sales-card-kicker {
        display:block;
        margin-bottom:5px;
        color:var(--sales-primary-strong);
        font:700 11px/1.3 var(--font-mono);
        letter-spacing:.06em;
        text-transform:uppercase;
    }

    .sales-card-head h2,
    .sales-section h3 {
        margin:0;
        color:var(--sales-foreground);
        font-weight:600;
        line-height:1.3;
    }

    .sales-card-head h2 { font-size:19px;letter-spacing:-.015em; }

    .sales-card-head p {
        max-width:68ch;
        margin:6px 0 0;
        color:var(--sales-muted-foreground);
        font-size:13px;
        line-height:1.6;
        text-wrap:pretty;
    }

    .sales-form-grid {
        display:grid;
        grid-template-columns:minmax(0,1.15fr) minmax(260px,.85fr);
        gap:24px;
    }

    .sales-section { min-width:0; }
    .sales-section + .sales-section { padding-left:24px;border-left:1px solid var(--sales-border); }
    .sales-section h3 { margin-bottom:14px;font-size:15px; }
    .sales-form-stack { display:grid;gap:16px; }
    .sales-field { display:flex;flex-direction:column;gap:7px;margin:0; }

    .sales-field>span,
    .sales-page .lt-label {
        color:var(--sales-foreground);
        font-size:13px;
        font-weight:600;
        line-height:1.45;
    }

    .sales-page input:not([type="hidden"]),
    .sales-page textarea {
        display:block;
        width:100%;
        min-height:42px;
        margin:0;
        padding:10px 12px;
        border:1px solid var(--sales-border);
        border-radius:var(--sales-radius);
        background:var(--sales-card);
        color:var(--sales-foreground);
        font:400 14px/1.5 var(--font-sans);
        box-shadow:0 1px 2px rgba(15,23,42,.025);
        transition:border-color 150ms ease,box-shadow 150ms ease;
    }

    .sales-page textarea { min-height:110px;resize:vertical; }

    .sales-page input:not([type="hidden"]):focus,
    .sales-page textarea:focus {
        border-color:var(--sales-ring);
        outline:0;
        box-shadow:0 0 0 3px rgba(59,130,246,.14);
    }

    .sales-phone-list { display:grid;gap:10px; }

    .sales-phone-row {
        display:grid;
        grid-template-columns:minmax(130px,.7fr) minmax(180px,1.2fr) 40px;
        align-items:end;
        gap:10px;
        padding:12px;
        border:1px solid var(--sales-border);
        border-radius:10px;
        background:var(--sales-muted);
    }

    .sales-icon-btn {
        display:inline-grid;
        place-items:center;
        width:40px;
        height:42px;
        padding:0;
        border:1px solid var(--sales-border);
        border-radius:var(--sales-radius);
        background:var(--sales-card);
        color:#b91c1c;
        cursor:pointer;
        transition:background-color 150ms ease,border-color 150ms ease;
    }

    .sales-icon-btn:hover { border-color:#fecaca;background:#fef2f2; }
    .sales-icon-btn .i { width:17px;height:17px; }

    .sales-btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-height:42px;
        gap:7px;
        padding:10px 15px;
        border:1px solid var(--sales-primary);
        border-radius:var(--sales-radius);
        background:var(--sales-primary);
        color:#fff;
        font:600 13px/1.2 var(--font-sans);
        cursor:pointer;
        transition:background-color 150ms ease,border-color 150ms ease;
    }

    .sales-btn:hover { border-color:var(--sales-primary-strong);background:var(--sales-primary-strong); }
    .sales-btn.secondary { border-color:var(--sales-border);background:var(--sales-card);color:var(--sales-foreground); }
    .sales-btn.secondary:hover { border-color:#bfdbfe;background:var(--sales-accent);color:var(--sales-accent-foreground); }
    .sales-btn .i { width:16px;height:16px; }
    .sales-add { margin-top:11px; }

    .sales-actions {
        display:flex;
        justify-content:flex-end;
        margin-top:20px;
        padding-top:16px;
        border-top:1px solid var(--sales-border);
    }

    .sales-empty {
        padding:18px;
        border:1px dashed var(--sales-border);
        border-radius:10px;
        background:var(--sales-muted);
        color:var(--sales-muted-foreground);
        font-size:13px;
        line-height:1.6;
    }

    .sales-page .lt { min-width:0;margin:0; }
    .sales-page .lt-label { margin-bottom:7px; }
    .sales-page .lt-bar { display:none; }
    .sales-page .lt-pane { display:none; }
    .sales-page .lt-pane.on { display:block; }

    .sales-page .lt-help {
        display:block;
        margin-top:6px;
        color:var(--sales-muted-foreground);
        font-size:12px;
        font-weight:400;
        line-height:1.55;
    }

    @media(max-width:760px) {
        .sales-head,.sales-locale-switch { align-items:stretch;flex-direction:column; }
        .sales-head-actions,.sales-head-actions .sales-btn,.sales-head-actions .admin-view-page { width:100%; }
        .sales-locale-options { display:grid;grid-template-columns:repeat(3,minmax(0,1fr)); }
        .sales-locale-button { width:100%; }
        .sales-form-grid { grid-template-columns:1fr; }
        .sales-section + .sales-section { padding-top:20px;padding-left:0;border-top:1px solid var(--sales-border);border-left:0; }
        .sales-phone-row { grid-template-columns:1fr 1fr 40px; }
        .sales-actions .sales-btn { width:100%; }
    }

    @media(max-width:520px) {
        .sales-card { padding:17px; }
        .sales-phone-row { grid-template-columns:1fr 40px; }
        .sales-phone-row .sales-field:first-child { grid-column:1 / -1; }
        .sales-icon-btn { grid-column:2;grid-row:2; }
        .sales-page input:not([type="hidden"]),.sales-page textarea { font-size:16px; }
    }

    @media(prefers-reduced-motion:reduce) {
        .sales-page *,.sales-page *::before,.sales-page *::after { transition-duration:.01ms!important; }
    }
</style>
@endpush

@section('content')
<div class="sales-page">
    <header class="sales-head">
        <div class="sales-head-copy">
            <!-- <span class="sales-eyebrow">
                <svg class="i" aria-hidden="true"><use href="#i-phone"/></svg>
                Kontak publik
            </span> -->
            <h1>Kontak &amp; Kantor Pusat</h1>
            <p>Kelola nomor WhatsApp, email, jam kerja, dan teks halaman Kontak. Data kantor pusat juga digunakan oleh halaman Lokasi Plant.</p>
        </div>
        <div class="sales-head-actions">
            <x-admin-view-page :href="route('contact')" page="Kontak" />
        </div>
    </header>

    <div class="sales-locale-switch">
        <div class="sales-locale-copy">
            <strong>Bahasa konten</strong>
            <span>Pilihan ini berlaku untuk jam kerja dan seluruh teks terjemahan pada halaman ini.</span>
        </div>
        <div class="sales-locale-options" role="group" aria-label="Pilih bahasa konten">
            <button type="button" class="sales-locale-button" data-sales-locale="id" aria-pressed="true" aria-label="Edit konten Bahasa Indonesia">ID</button>
            <button type="button" class="sales-locale-button" data-sales-locale="en" aria-pressed="false" aria-label="Edit konten Bahasa Inggris">EN</button>
            <button type="button" class="sales-locale-button" data-sales-locale="zh" aria-pressed="false" aria-label="Edit konten Bahasa Mandarin">中文</button>
        </div>
        <span class="sales-sr-only" role="status" aria-live="polite" data-sales-locale-status>Bahasa Indonesia dipilih.</span>
    </div>

    @if (session('status'))
        <div class="sales-alert success" role="status">
            <strong>Tersimpan</strong>
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="sales-alert" role="alert">
            <strong>Periksa kembali data berikut</strong>
            <ul>
                @foreach (collect($errors->all())->unique() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.sales.hq') }}" class="sales-card">
        @csrf
        @method('PUT')

        <header class="sales-card-head">
            <div>
                <span class="sales-card-kicker">Kantor pusat</span>
                <h2>Nomor &amp; informasi HQ{{ $hq ? ' · '.$hq->name : '' }}</h2>
                <p>Nomor yang sama tampil di halaman Kontak, peta, dan popup lokasi. Tautan WhatsApp dibuat otomatis.</p>
            </div>
        </header>

        @if ($errors->has('hq'))
            <div class="sales-alert">{{ $errors->first('hq') }}</div>
        @elseif (! $hq)
            <div class="sales-empty">Belum ada Plant yang ditandai sebagai HQ. Aktifkan “Kantor pusat (HQ)” dari menu Lokasi Plant.</div>
        @else
            @php
                $oldPhones = old('phones');
                $rows = $oldPhones !== null
                    ? collect($oldPhones)->map(fn ($value, $index) => [
                        'name' => old('phone_names.'.$index, ''),
                        'phone' => $value,
                    ])->all()
                    : $hq->phoneEntries();
                if (! $rows) {
                    $rows = [['name' => '', 'phone' => '']];
                }
            @endphp

            <div class="sales-form-grid">
                <section class="sales-section" aria-labelledby="salesPhonesTitle">
                    <h3 id="salesPhonesTitle">Nomor telepon &amp; WhatsApp</h3>
                    <div class="sales-phone-list" id="salesPhones">
                        @foreach ($rows as $row)
                            <div class="sales-phone-row">
                                <label class="sales-field">
                                    <span>Label</span>
                                    <input name="phone_names[]" value="{{ $row['name'] }}" maxlength="100" placeholder="Nama sales, opsional">
                                </label>
                                <label class="sales-field">
                                    <span>Nomor telepon</span>
                                    <input type="tel" name="phones[]" value="{{ $row['phone'] }}" maxlength="50" placeholder="0857 8787 8787">
                                </label>
                                <button type="button" class="sales-icon-btn js-sales-delete" aria-label="Hapus nomor ini">
                                    <svg class="i" aria-hidden="true"><use href="#i-trash"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <template id="salesPhoneTemplate">
                        <div class="sales-phone-row">
                            <label class="sales-field">
                                <span>Label</span>
                                <input name="phone_names[]" maxlength="100" placeholder="Nama sales, opsional">
                            </label>
                            <label class="sales-field">
                                <span>Nomor telepon</span>
                                <input type="tel" name="phones[]" maxlength="50" placeholder="0857 8787 8787">
                            </label>
                            <button type="button" class="sales-icon-btn js-sales-delete" aria-label="Hapus nomor ini">
                                <svg class="i" aria-hidden="true"><use href="#i-trash"/></svg>
                            </button>
                        </div>
                    </template>

                    <button type="button" class="sales-btn secondary sales-add" id="salesAddPhone">
                        <svg class="i" aria-hidden="true"><use href="#i-plus"/></svg>
                        Tambah nomor
                    </button>
                </section>

                <section class="sales-section" aria-labelledby="salesDetailsTitle">
                    <h3 id="salesDetailsTitle">Detail kontak</h3>
                    <div class="sales-form-stack">
                        <label class="sales-field">Email
                            <input type="email" name="email" maxlength="150" value="{{ old('email', $hq->email) }}" placeholder="marketing@newarmada.co.id">
                        </label>

                        <x-lang-tabs name="working_hours" label="Jam kerja" mode="suffix"
                            :values="[
                                'id' => old('working_hours', $hq->working_hours),
                                'en' => old('working_hours_en', $hq->working_hours_en),
                                'zh' => old('working_hours_zh', $hq->working_hours_zh),
                            ]" />
                    </div>
                </section>
            </div>

            <div class="sales-actions">
                <button type="submit" class="sales-btn">
                    <svg class="i" aria-hidden="true"><use href="#i-check"/></svg>
                    Simpan informasi HQ
                </button>
            </div>
        @endif
    </form>

    <form method="POST" action="{{ route('admin.sales.texts') }}" class="sales-card">
        @csrf

        <header class="sales-card-head">
            <div>
                <span class="sales-card-kicker">Halaman publik</span>
                <h2>Teks halaman Kontak</h2>
                <p>Isi terjemahan yang belum tersedia akan menggunakan Bahasa Indonesia pada website.</p>
            </div>
        </header>

        <div class="sales-form-stack">
            <x-lang-tabs name="contact_title" label="Judul halaman" :values="$texts['contact_title']" />
            <x-lang-tabs name="contact_lead" label="Kalimat pembuka (cetak tebal)" :values="$texts['contact_lead']" />
            <x-lang-tabs name="contact_intro" label="Paragraf pengantar" type="textarea" :rows="3" :values="$texts['contact_intro']" />
            <x-lang-tabs name="contact_sales_heading" label="Judul daftar sales" :values="$texts['contact_sales_heading']" />
            <x-lang-tabs name="contact_wa_greeting" label="Awal pesan WhatsApp"
                help="Nama produk ditambahkan di belakang teks ini saat pengunjung datang dari halaman produk."
                :values="$texts['contact_wa_greeting']" />
        </div>

        <div class="sales-actions">
            <button type="submit" class="sales-btn">
                <svg class="i" aria-hidden="true"><use href="#i-check"/></svg>
                Simpan teks Kontak
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var localeButtons = [].slice.call(document.querySelectorAll('[data-sales-locale]'));
    var localeStatus = document.querySelector('[data-sales-locale-status]');
    var localeNames = {
        id: 'Bahasa Indonesia',
        en: 'Bahasa Inggris',
        zh: 'Bahasa Mandarin'
    };
    var currentLocale = 'id';

    function applyLocale(locale, options) {
        if (!localeNames[locale]) return;

        var settings = options || {};
        currentLocale = locale;

        document.querySelectorAll('.sales-page .lt-pane').forEach(function (pane) {
            pane.classList.toggle('on', pane.getAttribute('data-lt-pane') === locale);
        });

        document.querySelectorAll('.sales-page [data-lt-tab]').forEach(function (tab) {
            tab.classList.toggle('on', tab.getAttribute('data-lt-tab') === locale);
        });

        localeButtons.forEach(function (button) {
            button.setAttribute('aria-pressed', button.getAttribute('data-sales-locale') === locale ? 'true' : 'false');
        });

        if (settings.announce && localeStatus) {
            localeStatus.textContent = localeNames[locale] + ' dipilih.';
        }

        if (settings.persist) {
            try {
                localStorage.setItem('na-sales-locale', locale);
            } catch (error) {}
        }
    }

    localeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            applyLocale(button.getAttribute('data-sales-locale'), {
                announce: true,
                persist: true
            });
        });
    });

    try {
        var savedLocale = localStorage.getItem('na-sales-locale');
        if (savedLocale && localeNames[savedLocale]) currentLocale = savedLocale;
    } catch (error) {}

    applyLocale(currentLocale);

    var phoneList = document.getElementById('salesPhones');
    var addPhone = document.getElementById('salesAddPhone');
    var phoneTemplate = document.getElementById('salesPhoneTemplate');

    if (phoneList && addPhone && phoneTemplate) {
        addPhone.addEventListener('click', function () {
            var row = phoneTemplate.content.firstElementChild.cloneNode(true);
            phoneList.appendChild(row);
            var input = row.querySelector('input');
            if (input) input.focus();
        });

        phoneList.addEventListener('click', function (event) {
            var button = event.target.closest('.js-sales-delete');
            if (!button) return;

            var row = button.closest('.sales-phone-row');
            var rows = phoneList.querySelectorAll('.sales-phone-row');

            if (rows.length > 1) {
                row.remove();
                return;
            }

            row.querySelectorAll('input').forEach(function (input) {
                input.value = '';
            });
            row.querySelector('input').focus();
        });
    }
})();
</script>
@endpush
