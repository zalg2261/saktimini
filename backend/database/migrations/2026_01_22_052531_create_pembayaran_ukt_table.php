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
        Schema::create('pembayaran_ukt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->string('tahun_akademik');
            $table->integer('semester');
            $table->decimal('jumlah', 15, 2); // Jumlah pembayaran
            $table->enum('status', ['pending', 'lunas', 'tertunda', 'dibatalkan'])->default('pending');
            $table->date('tanggal_bayar')->nullable();
            $table->string('metode_pembayaran')->nullable(); // transfer, tunai, dll
            $table->string('bukti_pembayaran')->nullable(); // Path ke file bukti
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->index('mahasiswa_id');
            $table->index('tahun_akademik');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_ukt');
    }
};
