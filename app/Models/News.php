<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    public const CATEGORIES = ['Event', 'News'];
    public const LANGS = ['id', 'en', 'zh'];

    protected $table = 'news';

    protected $fillable = [
        'slug', 'title', 'category', 'published_at', 'thumbnail_path',
        'blocks', 'is_active', 'created_by',
    ];

    protected $casts = [
        'title'        => 'array',
        'blocks'       => 'array',
        'published_at' => 'date',
        'is_active'    => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Berita yang boleh tampil di website. */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)->whereDate('published_at', '<=', now());
    }

    /** Ambil teks sesuai bahasa. Jika kosong, jatuh ke bahasa Indonesia. */
    public function tr(string $field = 'title', ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $value = $this->{$field} ?? [];

        return (string) (($value[$locale] ?? '') !== '' ? $value[$locale] : ($value['id'] ?? ''));
    }

    /** URL file. Path "images/..." = file bawaan di public/images, selain itu di storage/public. */
    public static function fileUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'images/') ? asset($path) : asset('storage/' . $path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return self::fileUrl($this->thumbnail_path);
    }

    /** Data untuk mengisi form "Ubah berita" di sisi kanan (dibaca JavaScript). */
    public function toEditArray(): array
    {
        $empty = ['id' => '', 'en' => '', 'zh' => ''];

        return [
            'id'            => $this->id,
            'update_url'    => route('admin.news.update', $this),
            'title'         => array_merge($empty, $this->title ?? []),
            'category'      => $this->category,
            'published_at'  => $this->published_at?->format('Y-m-d'),
            'is_active'     => $this->is_active,
            'thumbnail_url' => $this->thumbnail_url,
            'blocks'        => collect($this->blocks ?? [])->map(function ($b) use ($empty) {
                if (($b['type'] ?? 'p') === 'i') {
                    return [
                        'type'      => 'i',
                        'image'     => $b['image'] ?? '',
                        'image_url' => self::fileUrl($b['image'] ?? null),
                        'caption'   => array_merge($empty, $b['caption'] ?? []),
                    ];
                }

                return ['type' => 'p', 'text' => array_merge($empty, $b['text'] ?? [])];
            })->values()->all(),
        ];
    }
}
