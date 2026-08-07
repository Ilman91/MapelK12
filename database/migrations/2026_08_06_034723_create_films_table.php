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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->unique();
            $table->string('durasi');
            $table->string('rating');
            $table->text('deskripsi');
            $table->date('tahun_rilis');
            $table->string('poster')->nullable();
            $table->foreignId('id_genre')->constrained('genres')->cascadeOnDelete();
            $table->string('sutradara');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('aktor_films', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_film')->constrained('films')->cascadeOnDelete();
            $table->foreignId('id_aktor')->constrained('aktors')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
        Schema::dropIfExists('aktor_films');
    }
};
