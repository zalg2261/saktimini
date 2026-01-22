<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\Dosen;
use App\Models\MataKuliah;

class MateriSeeder extends Seeder
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

        $materiTemplates = [
            ['judul' => 'Pengenalan Konsep Dasar', 'tipe' => 'pdf'],
            ['judul' => 'Slide Presentasi Pertemuan 1', 'tipe' => 'ppt'],
            ['judul' => 'Video Tutorial', 'tipe' => 'video'],
            ['judul' => 'Latihan Soal', 'tipe' => 'pdf'],
            ['judul' => 'Referensi Tambahan', 'tipe' => 'link'],
        ];

        foreach ($mataKuliahs as $mk) {
            if (!$mk->dosen) {
                continue;
            }

            // Create 2-3 materi per mata kuliah
            $jumlahMateri = rand(2, 3);
            $selectedTemplates = collect($materiTemplates)->random($jumlahMateri);

            foreach ($selectedTemplates as $template) {
                Materi::firstOrCreate(
                    [
                        'dosen_id' => $mk->dosen_id,
                        'mata_kuliah_id' => $mk->id,
                        'judul' => $mk->nama . ' - ' . $template['judul'],
                    ],
                    [
                        'deskripsi' => 'Materi pembelajaran untuk ' . $mk->nama,
                        'tipe' => $template['tipe'],
                        'file_path' => $template['tipe'] === 'link' ? 'https://example.com/materi' : 'materi/' . str_replace(' ', '_', strtolower($mk->nama)) . '_' . str_replace(' ', '_', strtolower($template['judul'])) . '.' . $template['tipe'],
                        'file_name' => $template['tipe'] === 'link' ? null : $template['judul'] . '.' . $template['tipe'],
                    ]
                );
            }
        }

        $this->command->info('✅ Materi seeded successfully!');
    }
}
