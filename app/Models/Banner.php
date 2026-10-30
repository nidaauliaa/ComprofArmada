<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'is_active', 'image_path',
        'tag', 'title', 'headline', 'description', 'button_text', 'button_link',
        'tag_en', 'title_en', 'headline_en', 'description_en', 'button_text_en',
        'tag_zh', 'title_zh', 'headline_zh', 'description_zh', 'button_text_zh',
        'sort_order'
    ];

    // Helper untuk mengambil teks sesuai bahasa aktif (default: id)
    public function t($field)
    {
        $lang = app()->getLocale(); // 'id', 'en', atau 'zh'
        if ($lang !== 'id' && !empty($this->{$field . '_' . $lang})) {
            return $this->{$field . '_' . $lang};
        }
        return $this->{$field};
    }
}
