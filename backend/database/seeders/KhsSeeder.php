<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Khs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Krs;

class KhsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAkademik = '2024/2025';
        
        // Seed untuk semester 1 dan 2
        foreach ([1, 2] as $semester) {
            // Get KRS yang sudah disetujui
            $krsList = Krs::where('tahun_akademik', $tahunAkademik)
                ->where('semester', $semester)
                ->where('status', 'disetujui')
                ->with(['mahasiswa', 'mataKuliah'])
                ->get();

            if ($krsList->isEmpty()) {
                continue;
            }

            foreach ($krsList as $krs) {
                // Generate random nilai
                $nilaiUts = rand(60, 100);
                $nilaiUas = rand(60, 100);
                $nilaiTugas = rand(70, 100);
                $nilaiAkhir = round(($nilaiUts * 0.3) + ($nilaiUas * 0.4) + ($nilaiTugas * 0.3), 2);

                // Convert to huruf mutu
                $hurufMutu = $this->nilaiToHurufMutu($nilaiAkhir);

                Khs::firstOrCreate(
                    [
                        'mahasiswa_id' => $krs->mahasiswa_id,
                        'mata_kuliah_id' => $krs->mata_kuliah_id,
                        'tahun_akademik' => $tahunAkademik,
                        'semester' => $semester,
                    ],
                    [
                        'nilai_uts' => $nilaiUts,
                        'nilai_uas' => $nilaiUas,
                        'nilai_tugas' => $nilaiTugas,
                        'nilai_akhir' => $nilaiAkhir,
                        'huruf_mutu' => $hurufMutu,
                    ]
                );
            }
        }

        $this->command->info('✅ KHS seeded successfully!');
    }

    private function nilaiToHurufMutu(float $nilai): string
    {
        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 80 => 'B+',
            $nilai >= 75 => 'B',
            $nilai >= 70 => 'C+',
            $nilai >= 65 => 'C',
            $nilai >= 50 => 'D',
            default => 'E',
        };
    }
}
