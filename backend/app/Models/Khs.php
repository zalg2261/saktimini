<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khs extends Model
{
    protected $table = 'khs';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'tahun_akademik',
        'semester',
        'nilai_uts',
        'nilai_uas',
        'nilai_tugas',
        'nilai_akhir',
        'huruf_mutu',
    ];

    protected $casts = [
        'semester' => 'integer',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_tugas' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
