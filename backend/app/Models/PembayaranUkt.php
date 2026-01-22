<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranUkt extends Model
{
    protected $table = 'pembayaran_ukt';

    protected $fillable = [
        'mahasiswa_id',
        'tahun_akademik',
        'semester',
        'jumlah',
        'status',
        'tanggal_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'semester' => 'integer',
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
