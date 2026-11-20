@extends('layouts.admin')

@section('title', 'Tentang Kami')
@section('page_icon', 'text')
@section('page_title', 'Tentang Kami')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-gustam.css') }}?v={{ @filemtime(public_path('css/admin-gustam.css')) }}">
    <style>
        .ab-page {
            --ab-background: #ffffff;
            --ab-foreground: #333333;
            --ab-card: #ffffff;
            --ab-card-foreground: #333333;
            --ab-popover: #ffffff;
            --ab-primary: #3b82f6;
            --ab-primary-strong: #2563eb;
            --ab-primary-foreground: #ffffff;
            --ab-secondary: #f3f4f6;
            --ab-secondary-foreground: #4b5563;
            --ab-muted: #f9fafb;
            --ab-muted-foreground: #6b7280;
            --ab-accent: #e0f2fe;
            --ab-accent-foreground: #1e3a8a;
            --ab-destructive: #ef4444;
            --ab-border: #e5e7eb;
            --ab-input: #e5e7eb;
            --ab-ring: #3b82f6;
            --ab-radius: 0.5rem;
            --ab-mono: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            color: var(--ab-foreground);
            font-family: "Inter", "Barlow", system-ui, sans-serif;
        }

        .ab-page h1,
        .ab-page h2,
        .ab-page h3,
        .ab-page p {
            overflow-wrap: break-word;
        }

        .ab-page h1 {
            margin: 8px 0 6px;
            color: #262626;
            font-size: clamp(1.75rem, 3vw, 2.25rem);
            font-weight: 700;
            line-height: 1.12;
            letter-spacing: -0.035em;
            text-wrap: balance;
        }

        .ab-page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
            padding: 24px;
            background: var(--ab-card);
            border: 1px solid var(--ab-border);
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .ab-page-head-copy {
            max-width: 650px;
        }

        .ab-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--ab-primary-strong);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.09em;
            line-height: 1.3;
            text-transform: uppercase;
        }

        .ab-eyebrow .i {
            width: 16px;
            height: 16px;
        }

        .ab-page-lead {
            max-width: 64ch;
            margin: 0;
            color: var(--ab-muted-foreground);
            font-size: 14px;
            line-height: 1.65;
            text-wrap: pretty;
        }

        .ab-page-head-actions {
            display: flex;
            align-items: center;
            flex: none;
        }

        .ab-locale-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 14px;
            padding: 13px 15px;
            border: 1px solid var(--ab-border);
            border-radius: 12px;
            background: var(--ab-card);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.035);
        }

        .ab-locale-copy {
            display: grid;
            gap: 2px;
        }

        .ab-locale-copy strong {
            color: var(--ab-card-foreground);
            font-size: 13px;
            font-weight: 600;
        }

        .ab-locale-copy span {
            color: var(--ab-muted-foreground);
            font-size: 12px;
            line-height: 1.5;
        }

        .ab-locale-options {
            display: inline-flex;
            flex: none;
            gap: 3px;
            padding: 3px;
            border: 1px solid var(--ab-border);
            border-radius: 8px;
            background: var(--ab-secondary);
        }

        .ab-locale-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 54px;
            min-height: 36px;
            padding: 7px 12px;
            border: 1px solid transparent;
            border-radius: 6px;
            background: transparent;
            color: var(--ab-muted-foreground);
            font: 600 12px/1 "Inter", "Barlow", system-ui, sans-serif;
            cursor: pointer;
            transition: background-color 150ms ease, border-color 150ms ease, color 150ms ease;
        }

        .ab-locale-button:hover {
            background: var(--ab-card);
            color: var(--ab-card-foreground);
        }

        .ab-locale-button[aria-pressed="true"] {
            border-color: #bfdbfe;
            background: var(--ab-accent);
            color: var(--ab-accent-foreground);
        }

        .ab-locale-button:focus-visible {
            outline: 2px solid var(--ab-ring);
            outline-offset: 2px;
        }

        .ab-sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .ab-link-button,
        .ab-file-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            gap: 8px;
            padding: 9px 14px;
            border: 1px solid var(--ab-border);
            border-radius: var(--ab-radius);
            background: var(--ab-card);
            color: var(--ab-foreground);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.2;
            cursor: pointer;
            transition: background-color 150ms ease, border-color 150ms ease, color 150ms ease;
        }

        .ab-link-button:hover,
        .ab-file-action:hover {
            border-color: #bfdbfe;
            background: var(--ab-accent);
            color: var(--ab-accent-foreground);
        }

        .ab-link-button:focus-visible,
        .ab-file-action:focus-visible,
        .ab-tab:focus-visible,
        .ab-add-button:focus-visible,
        .ab-save-button:focus-visible {
            outline: 2px solid var(--ab-ring);
            outline-offset: 2px;
        }

        .ab-alert {
            margin-top: 16px;
            padding: 13px 15px;
            border: 1px solid #fecaca;
            border-radius: var(--ab-radius);
            background: #fef2f2;
            color: #991b1b;
            font-size: 13px;
            line-height: 1.55;
        }

        .ab-alert strong {
            display: block;
            margin-bottom: 3px;
            color: #7f1d1d;
            font-weight: 700;
        }

        .ab-alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .ab-form {
            margin-top: 16px;
        }

        .ab-tabs-shell {
            margin-bottom: 16px;
            padding: 7px;
            overflow-x: auto;
            background: var(--ab-card);
            border: 1px solid var(--ab-border);
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.035);
            scroll-margin-top: 76px;
            scrollbar-width: thin;
        }

        .ab-tabs {
            display: grid;
            grid-template-columns: repeat(5, minmax(138px, 1fr));
            gap: 5px;
            min-width: 730px;
        }

        .ab-tab {
            display: flex;
            align-items: center;
            min-height: 48px;
            gap: 10px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: var(--ab-muted-foreground);
            font: 600 13px/1.3 "Inter", "Barlow", system-ui, sans-serif;
            text-align: left;
            cursor: pointer;
            transition: background-color 150ms ease, border-color 150ms ease, color 150ms ease;
        }

        .ab-tab:hover {
            background: var(--ab-muted);
            color: var(--ab-card-foreground);
        }

        .ab-tab.is-active {
            border-color: #bfdbfe;
            background: var(--ab-accent);
            color: var(--ab-accent-foreground);
        }

        .ab-tab-number {
            display: grid;
            place-items: center;
            width: 26px;
            height: 26px;
            flex: none;
            border: 1px solid var(--ab-border);
            border-radius: 30px;
            background: var(--ab-card);
            color: var(--ab-muted-foreground);
            font: 600 11px/1 var(--ab-mono);
            font-variant-numeric: tabular-nums;
        }

        .ab-tab.is-active .ab-tab-number {
            border-color: var(--ab-primary);
            background: var(--ab-primary);
            color: var(--ab-primary-foreground);
        }

        .ab-panel[hidden] {
            display: none !important;
        }

        .ab-panel:focus-visible {
            outline: 2px solid var(--ab-ring);
            outline-offset: 4px;
            border-radius: 14px;
        }

        .ab-section-card {
            margin-bottom: 14px;
            padding: 22px;
            background: var(--ab-card);
            border: 1px solid var(--ab-border);
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.035);
        }

        .ab-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--ab-border);
        }

        .ab-card-kicker {
            display: block;
            margin-bottom: 5px;
            color: var(--ab-primary-strong);
            font: 700 11px/1.3 var(--ab-mono);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .ab-card-head h2,
        .ab-subcard h3 {
            margin: 0;
            color: var(--ab-card-foreground);
            font-weight: 600;
            line-height: 1.3;
            text-wrap: balance;
        }

        .ab-card-head h2 {
            font-size: 19px;
            letter-spacing: -0.015em;
        }

        .ab-card-head p {
            max-width: 70ch;
            margin: 6px 0 0;
            color: var(--ab-muted-foreground);
            font-size: 13px;
            line-height: 1.6;
            text-wrap: pretty;
        }

        .ab-form-stack {
            display: grid;
            gap: 16px;
        }

        .ab-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .ab-span-2 {
            grid-column: 1 / -1;
        }

        .ab-page label {
            margin: 0;
            color: var(--ab-card-foreground);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.45;
        }

        .ab-page label small {
            margin: 0;
            color: var(--ab-muted-foreground);
            font-size: 12px;
            font-weight: 400;
        }

        .ab-lbl {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .ab-lbl-inline {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ab-page input:not([type="checkbox"]):not([type="file"]),
        .ab-page select,
        .ab-page textarea {
            display: block;
            width: 100%;
            min-height: 42px;
            margin: 0;
            padding: 10px 12px;
            border: 1px solid var(--ab-input);
            border-radius: var(--ab-radius);
            background: var(--ab-card);
            color: var(--ab-card-foreground);
            font: 400 14px/1.5 "Inter", "Barlow", system-ui, sans-serif;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.025);
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }

        .ab-page select {
            cursor: pointer;
        }

        .ab-page textarea {
            min-height: 104px;
            resize: vertical;
        }

        .ab-page input:not([type="checkbox"]):not([type="file"]):focus,
        .ab-page select:focus,
        .ab-page textarea:focus {
            border-color: var(--ab-ring);
            outline: 0;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.14);
        }

        .ab-page input[type="checkbox"] {
            width: 17px;
            height: 17px;
            margin: 0;
            accent-color: var(--ab-primary);
        }

        .ab-page .lt {
            min-width: 0;
            margin: 0;
        }

        .ab-page .lt-label {
            margin-bottom: 7px;
            color: var(--ab-card-foreground);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.45;
        }

        .ab-page .lt-bar {
            display: none;
        }

        .ab-page .lt-tab {
            min-width: 42px;
            min-height: 28px;
            padding: 5px 11px;
            border-radius: 6px;
            color: var(--ab-muted-foreground);
            font: 600 11px/1 "Inter", "Barlow", system-ui, sans-serif;
            transition: background-color 150ms ease, color 150ms ease;
        }

        .ab-page .lt-tab:hover {
            color: var(--ab-card-foreground);
        }

        .ab-page .lt-tab.on {
            background: var(--ab-card);
            color: var(--ab-accent-foreground);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
        }

        .ab-page .lt-tab:focus-visible {
            outline: 2px solid var(--ab-ring);
            outline-offset: 1px;
        }

        .ab-page .lt-dot {
            top: 3px;
            right: 4px;
            width: 6px;
            height: 6px;
            background: #f59e0b;
        }

        .ab-page .lt-help {
            margin-top: 6px;
            color: var(--ab-muted-foreground);
            font-size: 12px;
            font-weight: 400;
            line-height: 1.55;
        }

        .ab-page .lt-pane {
            display: none;
        }

        .ab-page .lt-pane.on {
            display: block;
        }

        .ab-note {
            padding: 11px 13px;
            border: 1px solid #bfdbfe;
            border-radius: var(--ab-radius);
            background: #f0f9ff;
            color: #1e40af;
            font-size: 12.5px;
            line-height: 1.55;
        }

        .ab-repeat-list {
            display: grid;
            gap: 12px;
            margin-top: 18px;
            counter-reset: ab-repeat;
        }

        .ab-repeat-item {
            padding: 16px;
            border: 1px solid var(--ab-border);
            border-radius: 10px;
            background: var(--ab-muted);
            counter-increment: ab-repeat;
        }

        .ab-repeat-head {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 14px;
        }

        .ab-repeat-head strong {
            color: var(--ab-card-foreground);
            font-size: 13px;
            font-weight: 600;
        }

        .ab-repeat-no {
            display: inline-grid;
            place-items: center;
            min-width: 28px;
            height: 22px;
            padding: 0 6px;
            border-radius: 30px;
            background: var(--ab-accent);
            color: var(--ab-accent-foreground);
            font: 600 10px/1 var(--ab-mono);
            font-variant-numeric: tabular-nums;
        }

        .ab-repeat-no::before {
            content: counter(ab-repeat, decimal-leading-zero);
        }

        .ab-auto-badge {
            margin-left: auto;
            padding: 3px 7px;
            border: 1px solid var(--ab-border);
            border-radius: 999px;
            background: var(--ab-card);
            color: var(--ab-muted-foreground);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .ab-add-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            gap: 7px;
            margin-top: 12px;
            padding: 9px 13px;
            border: 1px solid var(--ab-border);
            border-radius: var(--ab-radius);
            background: var(--ab-card);
            color: var(--ab-card-foreground);
            font: 600 13px/1.2 "Inter", "Barlow", system-ui, sans-serif;
            cursor: pointer;
            transition: background-color 150ms ease, border-color 150ms ease;
        }

        .ab-add-button:hover {
            border-color: #bfdbfe;
            background: var(--ab-accent);
        }

        .ab-add-button .i {
            width: 16px;
            height: 16px;
        }

        .ab-profile-grid {
            display: grid;
            grid-template-columns: minmax(210px, 0.72fr) minmax(0, 1.6fr);
            gap: 20px;
            align-items: start;
        }

        .ab-upload-panel {
            display: grid;
            gap: 12px;
            padding: 14px;
            border: 1px solid var(--ab-border);
            border-radius: 10px;
            background: var(--ab-muted);
        }

        .ab-upload-preview {
            display: grid;
            place-items: center;
            width: 100%;
            aspect-ratio: 4 / 5;
            max-height: 250px;
            overflow: hidden;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            background: var(--ab-card);
            color: #94a3b8;
        }

        .ab-upload-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ab-upload-placeholder {
            display: grid;
            justify-items: center;
            gap: 7px;
            padding: 18px;
            color: var(--ab-muted-foreground);
            font-size: 12px;
            text-align: center;
        }

        .ab-upload-placeholder .i {
            width: 28px;
            height: 28px;
        }

        .ab-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .ab-file-action {
            width: 100%;
        }

        .ab-file-input:focus-visible + .ab-file-action {
            outline: 2px solid var(--ab-ring);
            outline-offset: 2px;
        }

        .ab-file-name {
            min-height: 18px;
            color: var(--ab-muted-foreground);
            font-size: 11px;
            line-height: 1.45;
            overflow-wrap: anywhere;
            text-align: center;
        }

        .ab-remove-photo {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-top: 10px;
            border-top: 1px solid var(--ab-border);
            color: #b91c1c;
            font-size: 12px;
            font-weight: 500;
        }

        .ab-split-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .ab-subcard {
            min-width: 0;
            padding: 18px;
            border: 1px solid var(--ab-border);
            border-radius: 10px;
            background: var(--ab-muted);
        }

        .ab-subcard h3 {
            margin-bottom: 14px;
            font-size: 15px;
        }

        .ab-savebar {
            position: sticky;
            bottom: max(14px, env(safe-area-inset-bottom));
            z-index: 15;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 18px;
            padding: 13px 15px;
            border: 1px solid var(--ab-border);
            border-radius: 12px;
            background: var(--ab-card);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        }

        .ab-save-note {
            max-width: 56ch;
            color: var(--ab-muted-foreground);
            font-size: 12px;
            line-height: 1.55;
        }

        .ab-save-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            gap: 8px;
            flex: none;
            padding: 10px 18px;
            border: 1px solid var(--ab-primary);
            border-radius: var(--ab-radius);
            background: var(--ab-primary);
            color: var(--ab-primary-foreground);
            font: 600 13px/1.2 "Inter", "Barlow", system-ui, sans-serif;
            cursor: pointer;
            transition: background-color 150ms ease, border-color 150ms ease;
        }

        .ab-save-button:hover {
            border-color: var(--ab-primary-strong);
            background: var(--ab-primary-strong);
        }

        .ab-save-button .i {
            width: 17px;
            height: 17px;
        }

        @media (max-width: 760px) {
            .ab-page-head {
                flex-direction: column;
                padding: 20px;
            }

            .ab-locale-switch {
                align-items: stretch;
                flex-direction: column;
                gap: 11px;
            }

            .ab-locale-options {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .ab-locale-button {
                width: 100%;
            }

            .ab-page-head-actions,
            .ab-link-button,
            .ab-page-head-actions .admin-view-page {
                width: 100%;
            }

            .ab-form-grid,
            .ab-profile-grid,
            .ab-split-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .ab-upload-preview {
                aspect-ratio: 16 / 10;
                max-height: 210px;
            }

            .ab-savebar {
                align-items: stretch;
                flex-direction: column;
                gap: 10px;
            }

            .ab-save-button {
                width: 100%;
            }
        }

        @media (max-width: 520px) {
            .ab-section-card {
                padding: 17px;
            }

            .ab-card-head {
                margin-bottom: 16px;
            }

            .ab-page input:not([type="checkbox"]):not([type="file"]),
            .ab-page select,
            .ab-page textarea {
                font-size: 16px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .ab-page *,
            .ab-page *::before,
            .ab-page *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $d = $about->data();
        $currentYear = (int) date('Y');

        // Default 4 kartu statistik jika kosong
        $statRows = old('stats', $d['stats'] ?? [
            ['value' => [], 'since' => 1962, 'label' => []],
            ['value' => [], 'since' => null, 'label' => []],
            ['value' => [], 'since' => null, 'label' => []],
            ['value' => [], 'since' => null, 'label' => []],
        ]);
        $statRows = is_array($statRows) ? array_values($statRows) : [];

        // Default 5 poin filosofi jika kosong
        $philRows = old('philosophy', $d['philosophy'] ?? [[], [], [], [], []]);
        $philRows = is_array($philRows) ? array_values($philRows) : [];
        $philRows['__I__'] = [];

        $histRows = old('history', $d['history'] ?? []);
        $histRows = is_array($histRows) ? $histRows : [];
        $histRows['__I__'] = ['from' => null, 'to' => null, 'text' => []];
    @endphp

    <div class="ab-page">
        <header class="ab-page-head">
            <div class="ab-page-head-copy">
                <!-- <span class="ab-eyebrow">
                    <svg class="i" aria-hidden="true"><use href="#i-text"/></svg>
                    Konten publik
                </span> -->
                <h1>Tentang Kami</h1>
                <p class="ab-page-lead">Kelola profil perusahaan, visi dan misi, filosofi, serta perjalanan New Armada dalam satu halaman.</p>
            </div>
            <div class="ab-page-head-actions">
                <x-admin-view-page :href="route('about')" page="Tentang Kami" />
            </div>
        </header>

        <div class="ab-locale-switch" data-locale-switch>
            <div class="ab-locale-copy">
                <strong>Bahasa konten</strong>
                <span>Pilihan ini berlaku untuk seluruh field di halaman Tentang Kami.</span>
            </div>
            <div class="ab-locale-options" role="group" aria-label="Pilih bahasa konten">
                <button type="button" class="ab-locale-button" data-about-locale="id" aria-pressed="true" aria-label="Edit konten Bahasa Indonesia">ID</button>
                <button type="button" class="ab-locale-button" data-about-locale="en" aria-pressed="false" aria-label="Edit konten Bahasa Inggris">EN</button>
                <button type="button" class="ab-locale-button" data-about-locale="zh" aria-pressed="false" aria-label="Edit konten Bahasa Mandarin">中文</button>
            </div>
            <span class="ab-sr-only" role="status" aria-live="polite" data-locale-status>Bahasa Indonesia dipilih.</span>
        </div>

        @if ($errors->any())
            <div class="ab-alert" role="alert">
                <strong>Periksa kembali data berikut</strong>
                <ul>
                    @foreach (collect($errors->all())->unique()->take(6) as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data" id="mainAboutForm" class="ab-form">
            @csrf
            @method('PUT')

            <div class="ab-tabs-shell">
                <div class="ab-tabs" role="tablist" aria-label="Bagian Tentang Kami" aria-orientation="horizontal">
                    <button type="button" class="ab-tab is-active" id="about-tab-sec-hero" role="tab" aria-selected="true" aria-controls="sec-hero" tabindex="0" data-target="sec-hero">
                        <span class="ab-tab-number" aria-hidden="true">01</span>
                        <span>Hero &amp; Statistik</span>
                    </button>
                    <button type="button" class="ab-tab" id="about-tab-sec-profile" role="tab" aria-selected="false" aria-controls="sec-profile" tabindex="-1" data-target="sec-profile">
                        <span class="ab-tab-number" aria-hidden="true">02</span>
                        <span>Profil &amp; Direktur</span>
                    </button>
                    <button type="button" class="ab-tab" id="about-tab-sec-visimisi" role="tab" aria-selected="false" aria-controls="sec-visimisi" tabindex="-1" data-target="sec-visimisi">
                        <span class="ab-tab-number" aria-hidden="true">03</span>
                        <span>Visi &amp; Misi</span>
                    </button>
                    <button type="button" class="ab-tab" id="about-tab-sec-philosophy" role="tab" aria-selected="false" aria-controls="sec-philosophy" tabindex="-1" data-target="sec-philosophy">
                        <span class="ab-tab-number" aria-hidden="true">04</span>
                        <span>Filosofi</span>
                    </button>
                    <button type="button" class="ab-tab" id="about-tab-sec-history" role="tab" aria-selected="false" aria-controls="sec-history" tabindex="-1" data-target="sec-history">
                        <span class="ab-tab-number" aria-hidden="true">05</span>
                        <span>Sejarah</span>
                    </button>
                </div>
            </div>

            {{-- 1. Bagian atas (Hero & Statistik) --}}
            <section class="ab-panel" id="sec-hero" role="tabpanel" aria-labelledby="about-tab-sec-hero" tabindex="0">
                <div class="ab-section-card">
                    <header class="ab-card-head">
                        <div>
                            <span class="ab-card-kicker">Identitas halaman</span>
                            <h2>Hero utama</h2>
                            <p>Bagian pertama yang dilihat pengunjung saat membuka halaman Tentang Kami.</p>
                        </div>
                    </header>

                    <div class="ab-form-stack">
                        <x-lang-tabs name="hero_badge" label="Label kecil di atas judul" :values="$d['hero_badge'] ?? []" />
                        <x-lang-tabs name="hero_title" label="Judul" :values="$d['hero_title'] ?? []" />
                        <x-lang-tabs name="hero_accent" label="Lanjutan judul (tampil berwarna)" :values="$d['hero_accent'] ?? []" />
                        <x-lang-tabs name="hero_lead" label="Kalimat pengantar" type="textarea" :rows="3" :values="$d['hero_lead'] ?? []" />
                    </div>
                </div>

                <div class="ab-section-card">
                    <header class="ab-card-head">
                        <div>
                            <span class="ab-card-kicker">Data pendukung</span>
                            <h2>Angka statistik</h2>
                            <p>Ringkasan pencapaian perusahaan yang tampil di bawah bagian hero.</p>
                        </div>
                    </header>

                    <p class="ab-note" id="statsSinceHelp">Statistik pertama dihitung otomatis dari tahun berdiri. Statistik lainnya diisi manual.</p>

                    <div class="ab-repeat-list">
                        @foreach ($statRows as $i => $r)
                            <div class="ab-repeat-item">
                                <div class="ab-repeat-head">
                                    <span class="ab-repeat-no" aria-hidden="true"></span>
                                    <strong>{{ $i === 0 ? 'Pengalaman perusahaan' : 'Statistik tambahan' }}</strong>
                                    @if ($i === 0)
                                        <span class="ab-auto-badge">Otomatis</span>
                                    @endif
                                </div>

                                <div class="ab-form-grid">
                                    @if ($i === 0)
                                        <label class="ab-lbl">Tahun berdiri
                                            <input type="number" min="1800" max="{{ $currentYear }}" name="stats[{{ $i }}][since]" value="{{ $r['since'] ?? 1962 }}" placeholder="mis. 1962" aria-describedby="statsSinceHelp">
                                        </label>
                                    @else
                                        <x-lang-tabs name="stats[{{ $i }}][value]" label="Angka (mis. 25 Ha, 1.500+)" :values="$r['value'] ?? []" />
                                    @endif

                                    <x-lang-tabs name="stats[{{ $i }}][label]" label="Keterangan" :values="$r['label'] ?? []" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- 2. Direktur & ringkasan perusahaan --}}
            <section class="ab-panel" id="sec-profile" role="tabpanel" aria-labelledby="about-tab-sec-profile" tabindex="0" hidden>
                <div class="ab-section-card">
                    <header class="ab-card-head">
                        <div>
                            <span class="ab-card-kicker">Profil perusahaan</span>
                            <h2>Direktur &amp; ringkasan</h2>
                            <p>Atur foto, identitas direktur, dan ringkasan perusahaan untuk halaman publik.</p>
                        </div>
                    </header>

                    <div class="ab-profile-grid">
                        <div class="ab-upload-panel">
                            <div class="ab-upload-preview">
                                @if ($about->photoUrl())
                                    <img src="{{ $about->photoUrl() }}" alt="Foto direktur saat ini">
                                @else
                                    <div class="ab-upload-placeholder">
                                        <svg class="i" aria-hidden="true"><use href="#i-image"/></svg>
                                        <span>Belum ada foto direktur</span>
                                    </div>
                                @endif
                            </div>

                            <input class="ab-file-input" id="directorPhoto" type="file" name="director_photo" accept="image/jpeg,image/png,image/webp" aria-describedby="directorPhotoHelp" data-photo-input>
                            <label class="ab-file-action" for="directorPhoto">
                                <svg class="i" aria-hidden="true"><use href="#i-up"/></svg>
                                Pilih foto
                            </label>
                            <span class="ab-file-name" data-file-name aria-live="polite">Belum ada file baru dipilih</span>
                            <p class="ab-note" id="directorPhotoHelp">JPG, PNG, atau WEBP. Maksimal 4 MB dengan rasio potret 4:5.</p>

                            @if ($about->photoUrl())
                                <label class="ab-remove-photo">
                                    <input type="checkbox" name="remove_director_photo" value="1">
                                    Hapus foto saat disimpan
                                </label>
                            @endif
                        </div>

                        <div class="ab-form-stack">
                            <div class="ab-form-grid">
                                <label class="ab-lbl">Nama direktur
                                    <input type="text" name="director_name" maxlength="150" value="{{ old('director_name', $d['director_name'] ?? '') }}">
                                </label>
                                <x-lang-tabs name="director_title" label="Jabatan" :values="$d['director_title'] ?? []" />
                            </div>

                            <x-lang-tabs name="overview_title" label="Judul ringkasan" :values="$d['overview_title'] ?? []" />
                            <x-lang-tabs name="overview_body" label="Isi ringkasan" type="textarea" :rows="10" :values="$d['overview_body'] ?? []"
                                         help="Pisahkan paragraf dengan satu baris kosong. **teks** untuk tebal, *teks* untuk miring." />
                        </div>
                    </div>
                </div>
            </section>

            {{-- 3. Visi & Misi --}}
            <section class="ab-panel" id="sec-visimisi" role="tabpanel" aria-labelledby="about-tab-sec-visimisi" tabindex="0" hidden>
                <div class="ab-section-card">
                    <header class="ab-card-head">
                        <div>
                            <span class="ab-card-kicker">Arah perusahaan</span>
                            <h2>Visi &amp; misi</h2>
                            <p>Pisahkan arah jangka panjang dan langkah utama perusahaan agar mudah dibaca.</p>
                        </div>
                    </header>

                    <div class="ab-split-grid">
                        <div class="ab-subcard">
                            <h3>Visi</h3>
                            <div class="ab-form-stack">
                                <x-lang-tabs name="vision_heading" label="Judul visi" :values="$d['vision_heading'] ?? []" />
                                <x-lang-tabs name="vision" label="Isi visi" type="textarea" :rows="5" :values="$d['vision'] ?? []"
                                             help="**teks** untuk tebal, *teks* untuk miring." />
                            </div>
                        </div>

                        <div class="ab-subcard">
                            <h3>Misi</h3>
                            <div class="ab-form-stack">
                                <x-lang-tabs name="mission_heading" label="Judul misi" :values="$d['mission_heading'] ?? []" />
                                <x-lang-tabs name="mission" label="Isi misi" type="textarea" :rows="5" :values="$d['mission'] ?? []"
                                             help="Satu baris untuk satu poin misi." />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 4. Filosofi --}}
            <section class="ab-panel" id="sec-philosophy" role="tabpanel" aria-labelledby="about-tab-sec-philosophy" tabindex="0" hidden>
                <div class="ab-section-card">
                    <header class="ab-card-head">
                        <div>
                            <span class="ab-card-kicker">Nilai perusahaan</span>
                            <h2>Filosofi perusahaan</h2>
                            <p>Kelola pengantar dan poin nilai yang menjadi dasar cara perusahaan bekerja.</p>
                        </div>
                    </header>

                    <div class="ab-form-stack">
                        <x-lang-tabs name="philosophy_title" label="Judul bagian" :values="$d['philosophy_title'] ?? []" />
                        <x-lang-tabs name="philosophy_sub" label="Kalimat di bawah judul" :values="$d['philosophy_sub'] ?? []" />
                    </div>

                    <div class="ab-repeat-list" data-repeat="philosophy">
                        @foreach ($philRows as $i => $r)
                            @if ($i === '__I__')<template id="tpl-philosophy">@endif
                            <div class="ab-repeat-item">
                                <div class="ab-repeat-head">
                                    <span class="ab-repeat-no" aria-hidden="true"></span>
                                    <strong>Poin filosofi</strong>
                                </div>
                                <x-lang-tabs name="philosophy[{{ $i }}]" label="Teks poin" :values="is_array($r) ? $r : []" />
                            </div>
                            @if ($i === '__I__')</template>@endif
                        @endforeach
                    </div>

                    <button type="button" class="ab-add-button" data-add="philosophy">
                        <svg class="i" aria-hidden="true"><use href="#i-plus"/></svg>
                        Tambah poin
                    </button>
                </div>
            </section>

            {{-- 5. Sejarah --}}
            <section class="ab-panel" id="sec-history" role="tabpanel" aria-labelledby="about-tab-sec-history" tabindex="0" hidden>
                <div class="ab-section-card">
                    <header class="ab-card-head">
                        <div>
                            <span class="ab-card-kicker">Perjalanan perusahaan</span>
                            <h2>Sejarah &amp; perjalanan</h2>
                            <p>Susun tonggak penting perusahaan berdasarkan rentang tahun dan keterangannya.</p>
                        </div>
                    </header>

                    <div class="ab-form-stack">
                        <x-lang-tabs name="history_title" label="Judul bagian" :values="$d['history_title'] ?? []" />
                        <x-lang-tabs name="history_sub" label="Kalimat di bawah judul" :values="$d['history_sub'] ?? []" />
                    </div>

                    <div class="ab-repeat-list" data-repeat="history">
                        @foreach ($histRows as $i => $r)
                            @if ($i === '__I__')<template id="tpl-history">@endif
                            <div class="ab-repeat-item">
                                <div class="ab-repeat-head">
                                    <span class="ab-repeat-no" aria-hidden="true"></span>
                                    <strong>Tahapan sejarah</strong>
                                </div>

                                <div class="ab-form-grid">
                                    <label class="ab-lbl">Tahun awal
                                        <select name="history[{{ $i }}][from]">
                                            <option value="">Pilih tahun</option>
                                            @for ($y = $currentYear; $y >= 1950; $y--)
                                                <option value="{{ $y }}" {{ (string) ($r['from'] ?? '') === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </label>

                                    <label class="ab-lbl">
                                        <span class="ab-lbl-inline">
                                            <span>Tahun akhir</span>
                                            <small>Kosongkan untuk satu tahun</small>
                                        </span>
                                        <select name="history[{{ $i }}][to]">
                                            <option value="">Tidak ada tahun akhir</option>
                                            @for ($y = $currentYear; $y >= 1950; $y--)
                                                <option value="{{ $y }}" {{ (string) ($r['to'] ?? '') === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </label>
                                </div>

                                <x-lang-tabs name="history[{{ $i }}][text]" label="Keterangan" type="textarea" :rows="3" :values="$r['text'] ?? []"
                                             help="**teks** untuk tebal, *teks* untuk miring." />
                            </div>
                            @if ($i === '__I__')</template>@endif
                        @endforeach
                    </div>

                    <button type="button" class="ab-add-button" data-add="history">
                        <svg class="i" aria-hidden="true"><use href="#i-plus"/></svg>
                        Tambah tahapan
                    </button>
                </div>
            </section>

            <div class="ab-savebar">
                <span class="ab-save-note">Periksa kembali isi setiap bahasa sebelum menyimpan. Perubahan akan langsung digunakan pada halaman publik.</span>
                <button type="submit" class="ab-save-button">
                    <svg class="i" aria-hidden="true"><use href="#i-check"/></svg>
                    Simpan perubahan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
(function () {
  var counter = 0;
  var tabs = [].slice.call(document.querySelectorAll('.ab-tab'));
  var panels = [].slice.call(document.querySelectorAll('.ab-panel'));
  var localeButtons = [].slice.call(document.querySelectorAll('[data-about-locale]'));
  var localeStatus = document.querySelector('[data-locale-status]');
  var currentLocale = 'id';
  var localeNames = {
    id: 'Bahasa Indonesia',
    en: 'Bahasa Inggris',
    zh: 'Bahasa Mandarin'
  };

  function applyLocale(locale, root, options) {
    if (!localeNames[locale]) return;

    var settings = options || {};
    var scope = root || document;
    currentLocale = locale;

    [].slice.call(scope.querySelectorAll('.lt-pane')).forEach(function (pane) {
      pane.classList.toggle('on', pane.getAttribute('data-lt-pane') === locale);
    });

    [].slice.call(scope.querySelectorAll('[data-lt-tab]')).forEach(function (tab) {
      tab.classList.toggle('on', tab.getAttribute('data-lt-tab') === locale);
    });

    localeButtons.forEach(function (button) {
      button.setAttribute('aria-pressed', button.getAttribute('data-about-locale') === locale ? 'true' : 'false');
    });

    if (settings.announce && localeStatus) {
      localeStatus.textContent = localeNames[locale] + ' dipilih.';
    }

    if (settings.persist) {
      try {
        localStorage.setItem('na-about-locale', locale);
      } catch (error) {}
    }
  }

  localeButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      applyLocale(button.getAttribute('data-about-locale'), document, {
        announce: true,
        persist: true
      });
    });
  });

  try {
    var savedLocale = localStorage.getItem('na-about-locale');
    if (savedLocale && localeNames[savedLocale]) currentLocale = savedLocale;
  } catch (error) {}

  applyLocale(currentLocale, document);

  function activateTab(targetId, options) {
    var settings = options || {};
    var nextTab = tabs.filter(function (tab) {
      return tab.getAttribute('data-target') === targetId;
    })[0];
    var nextPanel = document.getElementById(targetId);

    if (!nextTab || !nextPanel) return;

    tabs.forEach(function (tab) {
      var isActive = tab === nextTab;
      tab.classList.toggle('is-active', isActive);
      tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
      tab.tabIndex = isActive ? 0 : -1;
    });

    panels.forEach(function (panel) {
      panel.hidden = panel !== nextPanel;
    });

    nextTab.scrollIntoView({ block: 'nearest', inline: 'nearest' });

    if (settings.focus) nextTab.focus();

    if (settings.updateHash !== false) {
      try {
        history.replaceState(null, '', '#' + targetId);
      } catch (error) {}
    }
  }

  tabs.forEach(function (tab, index) {
    tab.addEventListener('click', function () {
      activateTab(tab.getAttribute('data-target'));
    });

    tab.addEventListener('keydown', function (event) {
      var nextIndex = null;

      if (event.key === 'ArrowRight') nextIndex = (index + 1) % tabs.length;
      if (event.key === 'ArrowLeft') nextIndex = (index - 1 + tabs.length) % tabs.length;
      if (event.key === 'Home') nextIndex = 0;
      if (event.key === 'End') nextIndex = tabs.length - 1;

      if (nextIndex === null) return;
      event.preventDefault();
      activateTab(tabs[nextIndex].getAttribute('data-target'), { focus: true });
    });
  });

  var hashTarget = window.location.hash.replace('#', '');
  if (hashTarget && document.getElementById(hashTarget)) {
    activateTab(hashTarget, { updateHash: false });
  }

  [].slice.call(document.querySelectorAll('[data-add]')).forEach(function (btn) {
    btn.addEventListener('click', function () {
      var key = btn.getAttribute('data-add');
      var tpl = document.getElementById('tpl-' + key);
      var host = document.querySelector('[data-repeat="' + key + '"]');
      if (!tpl || !host) return;
      var id = 'n' + (++counter);
      var wrap = document.createElement('div');
      wrap.innerHTML = tpl.innerHTML.replace(/__I__/g, id).trim();
      var row = wrap.firstElementChild;
      if (!row) return;
      host.appendChild(row);
      applyLocale(currentLocale, row);
      var f = row.querySelector('select, input, textarea');
      if (f) f.focus();
    });
  });

  var photoInput = document.querySelector('[data-photo-input]');
  var photoName = document.querySelector('[data-file-name]');
  if (photoInput && photoName) {
    photoInput.addEventListener('change', function () {
      photoName.textContent = photoInput.files && photoInput.files.length
        ? photoInput.files[0].name
        : 'Belum ada file baru dipilih';
    });
  }
})();
</script>
@endpush
