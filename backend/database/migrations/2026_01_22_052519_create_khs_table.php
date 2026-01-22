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
        Schema::create('khs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onDelete('cascade');
            $table->string('tahun_akademik');
            $table->integer('semester');
            $table->decimal('nilai_uts', 5, 2)->nullable(); // 0.00 - 100.00
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable(); // Rata-rata
            $table->string('huruf_mutu')->nullable(); // A, B, C, D, E
            $table->timestamps();
            
            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'tahun_akademik', 'semester']);
            $table->index('mahasiswa_id');
            $table->index('mata_kuliah_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khs');
    }
};
