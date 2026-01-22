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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->string('nidn')->unique(); // Nomor Induk Dosen Nasional
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('prodi');
            $table->string('jabatan')->nullable(); // Lektor, Lektor Kepala, Profesor, dll
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('nidn');
            $table->index('email');
            $table->index('prodi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
