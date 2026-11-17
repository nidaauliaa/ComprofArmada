<?php

use App\Http\Controllers\Api\ContentController;
use Illuminate\Support\Facades\Route;

/*
| API Management (prefix otomatis /api). Hanya GET, hanya konten publik.
| Akses diatur di .env: API_REQUIRE_KEY / API_KEYS / API_ALLOWED_IPS (config/content_api.php).
*/
Route::prefix('v1')->middleware(['content.api', 'throttle:120,1'])->group(function () {
    Route::get('/', [ContentController::class, 'index'])->name('api.v1.index');
    Route::get('manifest', [ContentController::class, 'manifest'])->name('api.v1.manifest');
    Route::get('all', [ContentController::class, 'all'])->name('api.v1.all');
    Route::get('{resource}', [ContentController::class, 'show'])->where('resource', '[a-z_]+')->name('api.v1.show');
});
