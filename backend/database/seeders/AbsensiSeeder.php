<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliahs = MataKuliah::with('dosen')->get();

        if ($mataKuliahs->isEmpty()) {
            $this->command->warn('⚠️  Mata Kuliah not found. Please run MataKuliahSeeder first.');
            return;
        }

        // Generate absensi untuk 8 minggu terakhir (2 bulan)
        $startDate = Carbon::now()->subWeeks(8)->startOfWeek();
        $endDate = Carbon::now();

        $topics = [
            'Pengenalan Konsep Dasar',
            'Teori dan Praktik',
            'Studi Kasus',
            'Latihan dan Diskusi',
            'Review Materi',
            'Ujian Tengah Semester',
            'Presentasi Proyek',
        ];

        foreach ($mataKuliahs as $mk) {
            if (!$mk->dosen) {
                continue;
            }

            // Generate absensi per minggu (2x pertemuan per minggu)
            $currentDate = $startDate->copy();
            $topicIndex = 0;

            while ($currentDate <= $endDate) {
                // Pertemuan 1: Senin
                if ($currentDate->dayOfWeek === Carbon::MONDAY) {
                    Absensi::firstOrCreate(
                        [
                            'dosen_id' => $mk->dosen_id,
                            'mata_kuliah_id' => $mk->id,
                            'tanggal' => $currentDate->toDateString(),
                        ],
                        [
                            'jam_mulai' => '08:00:00',
                            'jam_selesai' => '10:00:00',
                            'topik' => $topics[$topicIndex % count($topics)],
                            'catatan' => 'Pertemuan ke-' . ($topicIndex + 1),
                        ]
                    );
                    $topicIndex++;
                }

                // Pertemuan 2: Rabu
                if ($currentDate->dayOfWeek === Carbon::WEDNESDAY) {
                    Absensi::firstOrCreate(
                        [
                            'dosen_id' => $mk->dosen_id,
                            'mata_kuliah_id' => $mk->id,
                            'tanggal' => $currentDate->toDateString(),
                        ],
                        [
                            'jam_mulai' => '10:00:00',
                            'jam_selesai' => '12:00:00',
                            'topik' => $topics[$topicIndex % count($topics)],
                            'catatan' => 'Pertemuan ke-' . ($topicIndex + 1),
                        ]
                    );
                    $topicIndex++;
                }

                $currentDate->addDay();
            }
        }

        $this->command->info('✅ Absensi seeded successfully!');
    }
}
