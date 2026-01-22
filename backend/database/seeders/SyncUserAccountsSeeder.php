<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SyncUserAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Starting user accounts sync...');

        $mahasiswas = Mahasiswa::all();
        $mahasiswaCount = 0;
        $mahasiswaUpdated = 0;

        foreach ($mahasiswas as $mahasiswa) {
            $user = User::where('email', $mahasiswa->email)->first();
            
            if (!$user) {
                User::create([
                    'name' => $mahasiswa->nama,
                    'email' => $mahasiswa->email,
                    'password' => Hash::make('password123'),
                    'role' => 'mahasiswa',
                ]);
                $mahasiswaCount++;
            } else {
                if ($user->name !== $mahasiswa->nama || $user->email !== $mahasiswa->email) {
                    $user->update([
                        'name' => $mahasiswa->nama,
                        'email' => $mahasiswa->email,
                    ]);
                    $mahasiswaUpdated++;
                }
            }
        }

        $this->command->info("✅ Mahasiswa: {$mahasiswaCount} created, {$mahasiswaUpdated} updated");

        $dosens = Dosen::all();
        $dosenCount = 0;
        $dosenUpdated = 0;

        foreach ($dosens as $dosen) {
            $user = User::where('email', $dosen->email)->first();
            
            if (!$user) {
                User::create([
                    'name' => $dosen->nama,
                    'email' => $dosen->email,
                    'password' => Hash::make('password123'),
                    'role' => 'dosen',
                ]);
                $dosenCount++;
            } else {
                if ($user->name !== $dosen->nama || $user->email !== $dosen->email) {
                    $user->update([
                        'name' => $dosen->nama,
                        'email' => $dosen->email,
                    ]);
                    $dosenUpdated++;
                }
            }
        }

        $this->command->info("✅ Dosen: {$dosenCount} created, {$dosenUpdated} updated");
        $this->command->info('✅ User accounts sync completed!');
        $this->command->warn('⚠️  Default password for all synced accounts: password123');
    }
}
