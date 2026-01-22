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
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // Kode mata kuliah (contoh: TI101)
            $table->string('nama');
            $table->string('prodi');
            $table->integer('sks'); // SKS (1-6)
            $table->integer('semester'); // Semester (1-8)
            $table->unsignedBigInteger('dosen_id')->nullable(); // Will add foreign key later
            $table->integer('kapasitas')->default(40); // Kapasitas kelas
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('kode');
            $table->index('prodi');
            $table->index('semester');
        });
        
        // Add foreign key after dosen table is created
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->foreign('dosen_id')->references('id')->on('dosen')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
