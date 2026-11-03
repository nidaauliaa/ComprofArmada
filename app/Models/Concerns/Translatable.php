<?php

namespace App\Models\Concerns;

/**
 * Terjemahan per kolom: name (Indonesia), name_en, name_zh.
 * $model->t('name') mengembalikan teks sesuai bahasa aktif; bila terjemahannya kosong, memakai Bahasa Indonesia.
 */
trait Translatable
{
    public function t(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();

        if ($locale !== 'id') {
            $value = $this->getAttribute($field.'_'.$locale);
            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        $base = $this->getAttribute($field);

        return $base === null ? null : (string) $base;
    }
}
