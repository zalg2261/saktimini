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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('prodi');
            $table->integer('angkatan');
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'dropout'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for optimization
            $table->index('nim');
            $table->index('email');
            $table->index('prodi');
            $table->index('angkatan');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
