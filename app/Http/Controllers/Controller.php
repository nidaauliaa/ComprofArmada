<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

abstract class Controller
{
    /** Hapus file unggahan di storage. File bawaan di storage/app/public/images/ tidak ikut dihapus. */
    protected function deletePublicFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
