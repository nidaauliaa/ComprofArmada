<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/** Pengaturan situs (key => value). Nilai multi bahasa disimpan sebagai JSON {"id":..,"en":..,"zh":..}. */
class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['key', 'value'];

    public static function allCached(): array
    {
        try {
            return Cache::remember('site.settings', 3600, fn () => static::query()->pluck('value', 'key')->all());
        } catch (\Throwable $e) {
            return []; // tabel belum dimigrasi
        }
    }

    public static function get(string $key, $default = null)
    {
        $v = static::allCached()[$key] ?? null;
        return ($v === null || $v === '') ? $default : $v;
    }

    public static function put(string $key, $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('site.settings');
    }

    /** Nilai multi bahasa untuk bahasa aktif; jika kosong pakai Indonesia. */
    public static function tr(string $key, ?string $locale = null): string
    {
        $raw = static::get($key);
        $arr = is_string($raw) ? json_decode($raw, true) : null;
        if (! is_array($arr)) {
            return (string) $raw;
        }
        $locale = $locale ?: app()->getLocale();
        return (string) (($arr[$locale] ?? '') !== '' ? $arr[$locale] : ($arr['id'] ?? ''));
    }
}
