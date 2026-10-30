<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('product_models', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
        $table->string('name'); // Contoh: Tourista 25, Skylander R25 DD
        $table->string('slug')->unique();
        $table->string('subtitle')->nullable(); // Contoh: Pariwisata · 25 kursi
        $table->string('image_path')->nullable(); // Foto utama bus
        $table->string('brand')->default('NEW ARMADA');
        $table->string('height')->nullable(); // contoh: 3,120 MM
        $table->string('width')->nullable();  // contoh: 2,280 MM
        $table->string('length')->nullable(); // contoh: 7,400 MM
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_models');
    }
};
