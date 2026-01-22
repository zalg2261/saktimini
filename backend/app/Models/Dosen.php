<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use SoftDeletes;

    protected $table = 'dosen';

    protected $fillable = [
        'nidn',
        'nama',
        'email',
        'prodi',
        'jabatan',
    ];

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
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
