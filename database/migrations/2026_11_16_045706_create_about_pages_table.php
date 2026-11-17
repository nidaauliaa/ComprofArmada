<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Halaman "Tentang Kami": satu baris data, isi teks (3 bahasa) disimpan sebagai JSON di kolom content. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->json('content')->nullable();
            $table->string('director_photo')->nullable(); // path di disk public
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
