<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// SISI WEB: sinkronisasi konten dari sistem admin tiap 5 menit (aktifkan dengan CONTENT_SYNC_SCHEDULE=true + CONTENT_SOURCE_URL)
Schedule::command('content:sync')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->when(fn () => config('content_api.schedule') && filled(config('content_api.source_url')));
