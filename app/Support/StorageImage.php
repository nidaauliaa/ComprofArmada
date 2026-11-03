<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * URL gambar dari storage/app/public (lewat link public/storage).
 * Jika file yang tercatat di database ternyata tidak ada di disk (mis. file unggahan belum dipindah ke server/komputer ini),
 * pakai gambar bawaan di storage/app/public/images supaya tampilan tidak kosong.
 */
class StorageImage
{
    public const DEFAULT = 'images/skylander-r25.jpeg';

    /** Gambar bawaan menurut kata pada slug/teks (untuk berita & produk). */
    public static function guess(?string $hint): string
    {
        $h = strtolower((string) $hint);

        return match (true) {
            str_contains($h, 'double') || str_contains($h, 'big-bus') || str_contains($h, 'big bus') => 'images/BIGBUS-DOUBLEDECKER.png',
            str_contains($h, 'medium') => 'images/MEDIUM-BUS.png',
            str_contains($h, 'intercity') => 'images/INTERCITY-2.png',
            str_contains($h, 'micro') => 'images/MICROBUS-2.png',
            str_contains($h, 'box') || str_contains($h, 'heavy') => 'images/BOX.png',
            default => self::DEFAULT,
        };
    }

    public static function exists(?string $path): bool
    {
        return $path && Storage::disk('public')->exists($path);
    }

    /** URL file; bila hilang -> gambar bawaan sesuai $hint. */
    public static function url(?string $path, ?string $hint = null): string
    {
        $use = self::exists($path) ? $path : self::guess($hint);

        return asset('storage/'.$use);
    }
}
