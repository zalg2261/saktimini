<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataKuliah;
use App\Models\Dosen;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get dosens by prodi
        $dosenTI = Dosen::where('prodi', 'Teknik Informatika')->get();
        $dosenSI = Dosen::where('prodi', 'Sistem Informasi')->get();

        $mataKuliahTI = [
            ['kode' => 'TI101', 'nama' => 'Pemrograman Dasar', 'sks' => 3, 'semester' => 1],
            ['kode' => 'TI102', 'nama' => 'Algoritma dan Struktur Data', 'sks' => 3, 'semester' => 1],
            ['kode' => 'TI201', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester' => 2],
            ['kode' => 'TI202', 'nama' => 'Basis Data', 'sks' => 3, 'semester' => 2],
            ['kode' => 'TI301', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester' => 3],
            ['kode' => 'TI302', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 3],
            ['kode' => 'TI401', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 4],
            ['kode' => 'TI402', 'nama' => 'Kecerdasan Buatan', 'sks' => 3, 'semester' => 4],
            ['kode' => 'TI501', 'nama' => 'Pemrograman Mobile', 'sks' => 3, 'semester' => 5],
            ['kode' => 'TI502', 'nama' => 'Keamanan Sistem Informasi', 'sks' => 3, 'semester' => 5],
        ];

        $mataKuliahSI = [
            ['kode' => 'SI101', 'nama' => 'Pengantar Sistem Informasi', 'sks' => 3, 'semester' => 1],
            ['kode' => 'SI102', 'nama' => 'Pemrograman Dasar', 'sks' => 3, 'semester' => 1],
            ['kode' => 'SI201', 'nama' => 'Analisis dan Perancangan Sistem', 'sks' => 3, 'semester' => 2],
            ['kode' => 'SI202', 'nama' => 'Basis Data', 'sks' => 3, 'semester' => 2],
            ['kode' => 'SI301', 'nama' => 'Sistem Informasi Manajemen', 'sks' => 3, 'semester' => 3],
            ['kode' => 'SI302', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester' => 3],
            ['kode' => 'SI401', 'nama' => 'E-Commerce', 'sks' => 3, 'semester' => 4],
            ['kode' => 'SI402', 'nama' => 'Data Mining', 'sks' => 3, 'semester' => 4],
        ];

        $dosenIndexTI = 0;
        foreach ($mataKuliahTI as $mk) {
            $dosen = $dosenTI[$dosenIndexTI % $dosenTI->count()];
            MataKuliah::firstOrCreate(
                ['kode' => $mk['kode']],
                [
                    'nama' => $mk['nama'],
                    'prodi' => 'Teknik Informatika',
                    'sks' => $mk['sks'],
                    'semester' => $mk['semester'],
                    'dosen_id' => $dosen->id,
                    'kapasitas' => 40,
                ]
            );
            $dosenIndexTI++;
        }

        $dosenIndexSI = 0;
        foreach ($mataKuliahSI as $mk) {
            $dosen = $dosenSI[$dosenIndexSI % $dosenSI->count()];
            MataKuliah::firstOrCreate(
                ['kode' => $mk['kode']],
                [
                    'nama' => $mk['nama'],
                    'prodi' => 'Sistem Informasi',
                    'sks' => $mk['sks'],
                    'semester' => $mk['semester'],
                    'dosen_id' => $dosen->id,
                    'kapasitas' => 40,
                ]
            );
            $dosenIndexSI++;
        }

        $this->command->info('✅ Mata Kuliah seeded successfully!');
    }
}
