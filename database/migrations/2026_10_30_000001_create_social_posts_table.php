<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('social_posts')) {
            return; // sudah ada (mis. dari impor SQL)
        }

        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 20);                 // instagram | facebook | tiktok
            $table->string('kind', 20)->nullable();         // post | reel | video
            $table->string('external_id');                  // kode post / id video (untuk cegah duplikat)
            $table->text('source_url');                     // link share yang ditempel marketing (sudah dibersihkan)
            $table->text('embed_url');                      // URL embed hasil konversi
            $table->unsignedSmallInteger('embed_height')->default(600);
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->json('oembed')->nullable();             // dokumen oEmbed (JSON)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['platform', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_posts');
    }
};
