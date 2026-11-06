<?php

namespace App\Models;

use App\Services\OembedService;
use Illuminate\Database\Eloquent\Model;

class SocialPost extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'oembed' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeVisible($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    /** URL embed hanya dipakai bila berasal dari domain resmi platform (mencegah iframe ke situs lain). */
    public function safeEmbedUrl(): ?string
    {
        $url = (string) $this->embed_url;

        // Data TikTok lama (embed/v2, menampilkan "related videos") otomatis memakai pemutar resmi yang hanya menampilkan video.
        if ($this->platform === 'tiktok' && preg_match('~tiktok\.com/(?:embed/(?:v\d/)?|player/v\d/)(\d{8,25})~', $url, $m)) {
            return OembedService::tiktokPlayerUrl($m[1]);
        }

        return OembedService::isAllowedEmbed($url) ? $url : null;
    }

    public function embedWidth(): int
    {
        return max(200, min(750, (int) ($this->embed_width ?: 350)));
    }

    public function embedHeight(): int
    {
        return max(150, min(1400, (int) ($this->embed_height ?: 600)));
    }

    /** JSON yang boleh diedit manual oleh marketing/IT di admin. */
    public function editableJson(): array
    {
        return [
            'platform' => $this->platform,
            'kind' => $this->kind,
            'title' => $this->title,
            'author' => $this->author,
            'source_url' => $this->source_url,
            'embed_url' => $this->embed_url,
            'width' => $this->embedWidth(),
            'height' => $this->embedHeight(),
            'thumbnail_url' => $this->thumbnail_url,
            'oembed' => $this->oembed ?: (object) [],
        ];
    }

    public function editableJsonText(): string
    {
        return json_encode($this->editableJson(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
