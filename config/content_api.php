<?php

/**
 * API Management: menghubungkan dashboard admin (superapp) dengan web company profile.
 *
 * SISI ADMIN (penyedia data)  -> /api/v1/*  (routes/api.php)
 * SISI WEB   (pengambil data) -> php artisan content:sync
 */
return [

    /* ---------- Sisi admin: siapa yang boleh memanggil API ---------- */

    // false = terbuka (hanya konten publik, read-only; curl tanpa header tetap jalan)
    // true  = wajib header "X-API-Key: <kunci>" atau "Authorization: Bearer <kunci>"
    'require_key' => (bool) env('API_REQUIRE_KEY', false),

    // Daftar kunci yang sah, pisahkan dengan koma. Contoh: API_KEYS=kunci-web-prod,kunci-web-uji
    'keys' => array_values(array_filter(array_map('trim', explode(',', (string) env('API_KEYS', ''))))),

    // (opsional) Batasi per alamat IP server web, pisahkan dengan koma. Kosong = semua IP.
    'allowed_ips' => array_values(array_filter(array_map('trim', explode(',', (string) env('API_ALLOWED_IPS', ''))))),

    // Lama cache JSON (detik). Cache otomatis dibersihkan saat konten diubah di admin. 0 = tanpa cache.
    'cache_seconds' => (int) env('API_CACHE_SECONDS', 300),

    /* ---------- Sisi web: dari mana mengambil data ---------- */

    // Alamat dasar sistem admin/superapp, mis. https://superapp.perusahaan.co.id (tanpa /api/v1)
    'source_url' => env('CONTENT_SOURCE_URL'),

    // Kunci yang dikirim ke sistem admin (harus salah satu dari API_KEYS di sisi admin)
    'source_key' => env('CONTENT_SOURCE_KEY'),

    // true = jadwalkan "content:sync" tiap 5 menit (butuh scheduler berjalan: php artisan schedule:work)
    'schedule' => (bool) env('CONTENT_SYNC_SCHEDULE', false),
];
