<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);                       // contoh: Magelang (HQ)
            $table->string('address', 500);
            $table->string('phone', 50)->nullable();
            $table->decimal('lat', 10, 7);                     // latitude, contoh -7.4950000
            $table->decimal('lng', 10, 7);                     // longitude, contoh 110.2200000
            $table->boolean('is_active')->default(true);       // tayang / disembunyikan
            $table->unsignedInteger('sort_order')->default(0); // urutan di daftar
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
