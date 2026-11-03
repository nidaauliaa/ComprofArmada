<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('social_posts', 'embed_width')) {
            return;
        }
        Schema::table('social_posts', function (Blueprint $table) {
            // lebar embed (px) — dibaca dari kode embed yang ditempel marketing
            $table->unsignedSmallInteger('embed_width')->default(350)->after('embed_url');
        });
    }

    public function down(): void
    {
        Schema::table('social_posts', function (Blueprint $table) {
            $table->dropColumn('embed_width');
        });
    }
};
