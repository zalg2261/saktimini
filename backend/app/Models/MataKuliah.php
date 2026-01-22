<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataKuliah extends Model
{
    use SoftDeletes;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode',
        'nama',
        'prodi',
        'sks',
        'semester',
        'dosen_id',
        'kapasitas',
    ];

    protected $casts = [
        'sks' => 'integer',
        'semester' => 'integer',
        'kapasitas' => 'integer',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function krs()
    {
        return $this->hasMany(Krs::class);
    }

    public function khs()
    {
        return $this->hasMany(Khs::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
