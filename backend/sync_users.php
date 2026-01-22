<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

echo "🔄 Starting user accounts sync...\n\n";

// Sync Mahasiswa
$mahasiswas = Mahasiswa::all();
$mahasiswaCount = 0;
$mahasiswaUpdated = 0;

foreach ($mahasiswas as $mahasiswa) {
    $user = User::where('email', $mahasiswa->email)->first();
    
    if (!$user) {
        // Create new user account
        User::create([
            'name' => $mahasiswa->nama,
            'email' => $mahasiswa->email,
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);
        $mahasiswaCount++;
    } else {
        // Update existing user if email or name changed
        if ($user->name !== $mahasiswa->nama || $user->email !== $mahasiswa->email) {
            $user->update([
                'name' => $mahasiswa->nama,
                'email' => $mahasiswa->email,
            ]);
            $mahasiswaUpdated++;
        }
    }
}

echo "✅ Mahasiswa: {$mahasiswaCount} created, {$mahasiswaUpdated} updated\n";

// Sync Dosen
$dosens = Dosen::all();
$dosenCount = 0;
$dosenUpdated = 0;

foreach ($dosens as $dosen) {
    $user = User::where('email', $dosen->email)->first();
    
    if (!$user) {
        // Create new user account
        User::create([
            'name' => $dosen->nama,
            'email' => $dosen->email,
            'password' => Hash::make('password123'),
            'role' => 'dosen',
        ]);
        $dosenCount++;
    } else {
        // Update existing user if email or name changed
        if ($user->name !== $dosen->nama || $user->email !== $dosen->email) {
            $user->update([
                'name' => $dosen->nama,
                'email' => $dosen->email,
            ]);
            $dosenUpdated++;
        }
    }
}

echo "✅ Dosen: {$dosenCount} created, {$dosenUpdated} updated\n";
echo "\n✅ User accounts sync completed!\n";
echo "⚠️  Default password for all synced accounts: password123\n";
