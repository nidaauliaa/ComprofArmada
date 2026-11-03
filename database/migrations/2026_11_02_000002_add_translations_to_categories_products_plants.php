<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Terjemahan EN / 中文 untuk kategori, produk, dan lokasi plant (Bahasa Indonesia tetap di kolom aslinya).
 * Idempoten: kolom yang sudah ada (mis. dari impor SQL) dilewati.
 * Berita dan banner sudah punya terjemahan sendiri (news.title/blocks JSON {id,en,zh}; banners.*_en / *_zh).
 */
return new class extends Migration
{
    private array $columns = [
        'categories' => ['name_en' => ['string', 'name'], 'name_zh' => ['string', 'name_en']],
        'product_models' => [
            'subtitle_en' => ['string', 'subtitle'], 'subtitle_zh' => ['string', 'subtitle_en'],
            'description_en' => ['text', 'description'], 'description_zh' => ['text', 'description_en'],
        ],
        'plants' => [
            'name_en' => ['string100', 'name'], 'name_zh' => ['string100', 'name_en'],
            'address_en' => ['string500', 'address'], 'address_zh' => ['string500', 'address_en'],
        ],
    ];

    public function up(): void
    {
        foreach ($this->columns as $tableName => $cols) {
            foreach ($cols as $col => [$type, $after]) {
                if (Schema::hasColumn($tableName, $col)) {
                    continue;
                }
                Schema::table($tableName, function (Blueprint $table) use ($col, $type, $after) {
                    $c = match ($type) {
                        'text' => $table->text($col),
                        'string100' => $table->string($col, 100),
                        'string500' => $table->string($col, 500),
                        default => $table->string($col),
                    };
                    $c->nullable()->after($after);
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $tableName => $cols) {
            $existing = array_values(array_filter(array_keys($cols), fn ($c) => Schema::hasColumn($tableName, $c)));
            if ($existing) {
                Schema::table($tableName, fn (Blueprint $t) => $t->dropColumn($existing));
            }
        }
    }
};
