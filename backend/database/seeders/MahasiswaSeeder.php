<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Teknik Komputer',
            'Manajemen',
            'Akuntansi',
            'Psikologi',
            'Hukum',
            'Kedokteran',
            'Farmasi',
            'Teknik Sipil',
        ];

        $statuses = ['aktif', 'aktif', 'aktif', 'aktif', 'cuti', 'lulus', 'dropout'];
        $angkatan = [2020, 2021, 2022, 2023, 2024];

        $firstNames = [
            'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar', 'Gita', 'Hadi', 'Indra', 'Joko',
            'Kartika', 'Lina', 'Maya', 'Nina', 'Oki', 'Putri', 'Rina', 'Sari', 'Toni', 'Umi',
            'Vina', 'Wati', 'Yani', 'Zaki', 'Ayu', 'Bayu', 'Cinta', 'Dedi', 'Eka', 'Fani'
        ];

        $lastNames = [
            'Santoso', 'Wijaya', 'Sari', 'Kurniawan', 'Prasetyo', 'Sari', 'Hidayat', 'Rahayu',
            'Nugroho', 'Sari', 'Putra', 'Sari', 'Wibowo', 'Sari', 'Purnomo', 'Sari', 'Saputra',
            'Sari', 'Setiawan', 'Sari', 'Kusuma', 'Sari', 'Maulana', 'Sari', 'Ramadhani', 'Sari'
        ];

        $usedEmails = [];
        $mahasiswa = [];

        for ($i = 1; $i <= 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $nama = $firstName . ' ' . $lastName;
            
            $nim = str_pad($i, 10, '0', STR_PAD_LEFT);
            $prodi = $prodis[array_rand($prodis)];
            $status = $statuses[array_rand($statuses)];
            $tahunAngkatan = $angkatan[array_rand($angkatan)];
            
            $emailBase = Str::slug($firstName . '.' . $lastName, '');
            $email = strtolower($emailBase . '@sakti.ac.id');

            $counter = 1;
            while (in_array($email, $usedEmails) || Mahasiswa::where('email', $email)->exists()) {
                $email = strtolower($emailBase . $counter . '@student.sakti.ac.id');
                $counter++;
            }
            
            $usedEmails[] = $email;

            $mahasiswa[] = [
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email,
                'prodi' => $prodi,
                'angkatan' => $tahunAngkatan,
                'status' => $status,
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ];
        }

        foreach ($mahasiswa as $data) {
            Mahasiswa::firstOrCreate(
                ['nim' => $data['nim']],
                $data
            );
        }

        $this->command->info('✅ 100 data mahasiswa berhasil dibuat!');
    }
}
