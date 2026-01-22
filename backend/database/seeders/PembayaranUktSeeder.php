<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PembayaranUkt;
use App\Models\Mahasiswa;

class PembayaranUktSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAkademik = '2024/2025';
        $semester = 1;

        // Get mahasiswa aktif (lebih banyak)
        $mahasiswas = Mahasiswa::where('status', 'aktif')->limit(60)->get();

        if ($mahasiswas->isEmpty()) {
            $this->command->warn('⚠️  Mahasiswa not found. Please run MahasiswaSeeder first.');
            return;
        }

        $jumlahUkt = [
            5000000, // 5 juta
            6000000, // 6 juta
            7000000, // 7 juta
            8000000, // 8 juta
        ];

        $metodePembayaran = ['Transfer Bank', 'Tunai', 'Virtual Account'];

        // Seed untuk semester 1 dan 2
        foreach ([1, 2] as $semester) {
            foreach ($mahasiswas as $mahasiswa) {
                // Random status (60% lunas, 30% pending, 5% tertunda, 5% dibatalkan)
                $rand = rand(1, 100);
                if ($rand <= 60) {
                    $status = 'lunas';
                    $tanggalBayar = now()->subDays(rand(1, 60));
                } elseif ($rand <= 90) {
                    $status = 'pending';
                    $tanggalBayar = null;
                } elseif ($rand <= 95) {
                    $status = 'tertunda';
                    $tanggalBayar = null;
                } else {
                    $status = 'dibatalkan';
                    $tanggalBayar = null;
                }

                PembayaranUkt::firstOrCreate(
                    [
                        'mahasiswa_id' => $mahasiswa->id,
                        'tahun_akademik' => $tahunAkademik,
                        'semester' => $semester,
                    ],
                    [
                        'jumlah' => $jumlahUkt[array_rand($jumlahUkt)],
                        'status' => $status,
                        'tanggal_bayar' => $tanggalBayar,
                        'metode_pembayaran' => $status === 'lunas' ? $metodePembayaran[array_rand($metodePembayaran)] : null,
                        'keterangan' => $status === 'lunas' ? 'Pembayaran lunas' : ($status === 'pending' ? 'Menunggu pembayaran' : null),
                    ]
                );
            }
        }

        $this->command->info('✅ Pembayaran UKT seeded successfully!');
    }
}
