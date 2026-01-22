<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'prodi',
        'angkatan',
        'status',
    ];

    protected $casts = [
        'angkatan' => 'integer',
    ];

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

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function pembayaranUkt()
    {
        return $this->hasMany(PembayaranUkt::class);
    }
}
