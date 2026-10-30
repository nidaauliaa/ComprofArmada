<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->string('image_path')->nullable();

            // Bahasa Indonesia (Default)
            $table->string('tag')->nullable();
            $table->string('title');
            $table->string('headline')->nullable(); 
            $table->text('description')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_link')->default('#produk');

            // Bahasa Inggris (EN)
            $table->string('tag_en')->nullable();
            $table->string('title_en')->nullable();
            $table->string('headline_en')->nullable();
            $table->text('description_en')->nullable();
            $table->string('button_text_en')->nullable();

            // Bahasa Mandarin (ZH)
            $table->string('tag_zh')->nullable();
            $table->string('title_zh')->nullable();
            $table->string('headline_zh')->nullable();
            $table->text('description_zh')->nullable();
            $table->string('button_text_zh')->nullable();

            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
