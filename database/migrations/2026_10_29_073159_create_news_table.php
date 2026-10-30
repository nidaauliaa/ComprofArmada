<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');                              // {"id":"...","en":"...","zh":"..."}
            $table->string('category', 20)->default('News');    // Event | News
            $table->date('published_at');                       // tanggal berita
            $table->string('thumbnail_path')->nullable();       // gambar kecil untuk slider & daftar
            $table->json('blocks')->nullable();                 // isi artikel: urutan paragraf dan gambar
            $table->boolean('is_active')->default(true);        // tayang / disembunyikan
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
