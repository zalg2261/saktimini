<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;

class KrsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAkademik = '2024/2025';
        
        // Seed untuk semester 1 dan 2
        foreach ([1, 2] as $semester) {
            // Get more mahasiswa (50 untuk semester 1, 30 untuk semester 2)
            $limit = $semester === 1 ? 50 : 30;
            $mahasiswas = Mahasiswa::where('status', 'aktif')->limit($limit)->get();
            $mataKuliahs = MataKuliah::all();

            if ($mahasiswas->isEmpty() || $mataKuliahs->isEmpty()) {
                $this->command->warn('⚠️  Mahasiswa or Mata Kuliah not found. Please run MahasiswaSeeder and MataKuliahSeeder first.');
                continue;
            }

            foreach ($mahasiswas as $mahasiswa) {
                // Get mata kuliah sesuai prodi dan semester
                $availableMataKuliah = $mataKuliahs
                    ->where('prodi', $mahasiswa->prodi)
                    ->where('semester', $semester);
                
                if ($availableMataKuliah->isEmpty()) {
                    continue;
                }

                // Random 3-5 mata kuliah per mahasiswa
                $jumlahMK = min(rand(3, 5), $availableMataKuliah->count());
                $selectedMK = $availableMataKuliah->random($jumlahMK);

                foreach ($selectedMK as $mk) {
                    $krs = Krs::firstOrCreate(
                        [
                            'mahasiswa_id' => $mahasiswa->id,
                            'mata_kuliah_id' => $mk->id,
                            'tahun_akademik' => $tahunAkademik,
                            'semester' => $semester,
                        ],
                        [
                            'status' => rand(0, 10) <= 7 ? 'disetujui' : (rand(0, 1) === 0 ? 'pending' : 'ditolak'), // 70% disetujui
                        ]
                    );
                    
                    // Update existing KRS to approved if it's pending
                    if ($krs->wasRecentlyCreated === false && $krs->status === 'pending') {
                        $krs->update(['status' => 'disetujui']);
                    }
                }
            }
        }

        $this->command->info('✅ KRS seeded successfully!');
    }
}
