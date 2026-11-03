<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('news', 'views')) {
            return;
        }
        Schema::table('news', function (Blueprint $table) {
            $table->unsignedInteger('views')->default(0)->after('is_active'); // untuk blok "Terpopuler"
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('views');
        });
    }
};
