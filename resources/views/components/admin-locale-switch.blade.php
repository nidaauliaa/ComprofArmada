@props([
    'storageKey' => 'na-admin-locale',
    'help' => 'Pilihan ini berlaku untuk seluruh field terjemahan pada halaman ini.',
])

<div class="admin-locale-slot" data-admin-locale-slot>
    <div class="admin-locale-switch" data-admin-locale-switch data-storage-key="{{ $storageKey }}">
        <div class="admin-locale-copy">
            <strong>Bahasa konten</strong>
            <span>{{ $help }}</span>
        </div>
        <div class="admin-locale-options" role="group" aria-label="Pilih bahasa konten">
            <button type="button" class="admin-locale-button" data-admin-locale="id" aria-pressed="true" aria-label="Edit konten Bahasa Indonesia">ID</button>
            <button type="button" class="admin-locale-button" data-admin-locale="en" aria-pressed="false" aria-label="Edit konten Bahasa Inggris">EN</button>
            <button type="button" class="admin-locale-button" data-admin-locale="zh" aria-pressed="false" aria-label="Edit konten Bahasa Mandarin">中文</button>
        </div>
        <span class="admin-sr-only" role="status" aria-live="polite" data-admin-locale-status>Bahasa Indonesia dipilih.</span>
    </div>
</div>
