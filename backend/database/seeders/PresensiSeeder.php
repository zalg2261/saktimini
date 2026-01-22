<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presensi;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Krs;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get KRS yang sudah disetujui
        $krsList = Krs::where('status', 'disetujui')
            ->with(['mahasiswa', 'mataKuliah'])
            ->get()
            ->groupBy('mata_kuliah_id');

        if ($krsList->isEmpty()) {
            $this->command->warn('⚠️  No approved KRS found. Please run KrsSeeder first.');
            return;
        }

        // Generate presensi untuk 8 minggu terakhir (2 bulan)
        $startDate = Carbon::now()->subWeeks(8)->startOfWeek();
        $endDate = Carbon::now();

        foreach ($krsList as $mataKuliahId => $krsItems) {
            $mataKuliah = $krsItems->first()->mataKuliah;
            
            // Generate presensi per minggu (2x pertemuan per minggu)
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                // Pertemuan 1: Senin
                if ($currentDate->dayOfWeek === Carbon::MONDAY) {
                    $this->createPresensi($krsItems, $mataKuliah, $currentDate->copy()->setTime(8, 0), $currentDate->copy()->setTime(10, 0));
                }
                
                // Pertemuan 2: Rabu
                if ($currentDate->dayOfWeek === Carbon::WEDNESDAY) {
                    $this->createPresensi($krsItems, $mataKuliah, $currentDate->copy()->setTime(10, 0), $currentDate->copy()->setTime(12, 0));
                }

                $currentDate->addDay();
            }
        }

        $this->command->info('✅ Presensi seeded successfully!');
    }

    private function createPresensi($krsItems, $mataKuliah, $jamMulai, $jamSelesai)
    {
        foreach ($krsItems as $krs) {
            // Random status (80% hadir, 10% izin, 5% sakit, 5% alpha)
            $rand = rand(1, 100);
            if ($rand <= 80) {
                $status = 'hadir';
            } elseif ($rand <= 90) {
                $status = 'izin';
            } elseif ($rand <= 95) {
                $status = 'sakit';
            } else {
                $status = 'alpha';
            }

            Presensi::firstOrCreate(
                [
                    'mahasiswa_id' => $krs->mahasiswa_id,
                    'mata_kuliah_id' => $mataKuliah->id,
                    'tanggal' => $jamMulai->toDateString(),
                ],
                [
                    'jam_mulai' => $jamMulai->toTimeString(),
                    'jam_selesai' => $jamSelesai->toTimeString(),
                    'status' => $status,
                    'keterangan' => $status === 'izin' ? 'Izin keperluan keluarga' : ($status === 'sakit' ? 'Sakit' : null),
                ]
            );
        }
    }
}
