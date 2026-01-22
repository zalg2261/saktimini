<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Prodi
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Prodi',
                'password' => Hash::make('password123'),
                'role' => 'admin_prodi',
            ]
        );

        // Admin Pusat
        User::firstOrCreate(
            ['email' => 'adminpusat@test.com'],
            [
                'name' => 'Admin Pusat',
                'password' => Hash::make('password123'),
                'role' => 'admin_pusat',
            ]
        );

        // Dosen
        User::firstOrCreate(
            ['email' => 'dosen@test.com'],
            [
                'name' => 'Dosen',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
            ]
        );

        // Mahasiswa
        User::firstOrCreate(
            ['email' => 'mahasiswa@test.com'],
            [
                'name' => 'Mahasiswa',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ]
        );

        $this->command->info('✅ User testing berhasil dibuat!');
    }
}
