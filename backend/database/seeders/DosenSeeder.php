<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosens = [
            [
                'nidn' => '0012345678',
                'nama' => 'Prof. Dr. Ahmad Hidayat, M.Kom',
                'email' => 'ahmad.hidayat@sakti.ac.id',
                'prodi' => 'Teknik Informatika',
                'jabatan' => 'Profesor',
            ],
            [
                'nidn' => '0012345679',
                'nama' => 'Dr. Siti Nurhaliza, S.Kom, M.T',
                'email' => 'siti.nurhaliza@sakti.ac.id',
                'prodi' => 'Teknik Informatika',
                'jabatan' => 'Lektor Kepala',
            ],
            [
                'nidn' => '0012345680',
                'nama' => 'Budi Santoso, S.Kom, M.Kom',
                'email' => 'budi.santoso@sakti.ac.id',
                'prodi' => 'Teknik Informatika',
                'jabatan' => 'Lektor',
            ],
            [
                'nidn' => '0012345681',
                'nama' => 'Dr. Rina Wati, S.Si, M.Si',
                'email' => 'rina.wati@sakti.ac.id',
                'prodi' => 'Sistem Informasi',
                'jabatan' => 'Lektor Kepala',
            ],
            [
                'nidn' => '0012345682',
                'nama' => 'Ahmad Fauzi, S.Kom, M.T',
                'email' => 'ahmad.fauzi@sakti.ac.id',
                'prodi' => 'Sistem Informasi',
                'jabatan' => 'Lektor',
            ],
            [
                'nidn' => '0012345683',
                'nama' => 'Dr. Maya Sari, S.Kom, M.Kom',
                'email' => 'maya.sari@sakti.ac.id',
                'prodi' => 'Teknik Informatika',
                'jabatan' => 'Lektor Kepala',
            ],
        ];

        foreach ($dosens as $dosenData) {
            // Create user for dosen
            $user = User::firstOrCreate(
                ['email' => $dosenData['email']],
                [
                    'name' => $dosenData['nama'],
                    'password' => Hash::make('password123'),
                    'role' => 'dosen',
                ]
            );

            // Create dosen
            Dosen::firstOrCreate(
                ['email' => $dosenData['email']],
                [
                    'nidn' => $dosenData['nidn'],
                    'nama' => $dosenData['nama'],
                    'prodi' => $dosenData['prodi'],
                    'jabatan' => $dosenData['jabatan'],
                ]
            );
        }

        $this->command->info('✅ Dosen seeded successfully!');
    }
}
