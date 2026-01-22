<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\Khs;
use App\Models\Presensi;
use App\Models\Penilaian;
use App\Models\Absensi;
use App\Models\Materi;
use App\Models\PembayaranUkt;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          📊 STATISTIK DATABASE SAKTI UNIVERSITY              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$tables = [
    'users' => User::class,
    'mahasiswa' => Mahasiswa::class,
    'dosen' => Dosen::class,
    'mata_kuliah' => MataKuliah::class,
    'krs' => Krs::class,
    'khs' => Khs::class,
    'presensi' => Presensi::class,
    'penilaian' => Penilaian::class,
    'absensi' => Absensi::class,
    'materi' => Materi::class,
    'pembayaran_ukt' => PembayaranUkt::class,
];

echo "┌─────────────────────────────┬──────────┐\n";
echo "│ Tabel                       │ Jumlah   │\n";
echo "├─────────────────────────────┼──────────┤\n";

$total = 0;
foreach ($tables as $name => $model) {
    try {
        $count = $model::count();
        $total += $count;
        echo "│ " . str_pad(ucfirst($name), 27, ' ', STR_PAD_RIGHT) . " │ " . str_pad(number_format($count), 8, ' ', STR_PAD_LEFT) . " │\n";
    } catch (\Exception $e) {
        echo "│ " . str_pad(ucfirst($name), 27, ' ', STR_PAD_RIGHT) . " │ " . str_pad("ERROR", 8, ' ', STR_PAD_LEFT) . " │\n";
    }
}

echo "├─────────────────────────────┼──────────┤\n";
echo "│ " . str_pad("TOTAL", 27, ' ', STR_PAD_RIGHT) . " │ " . str_pad(number_format($total), 8, ' ', STR_PAD_LEFT) . " │\n";
echo "└─────────────────────────────┴──────────┘\n\n";

// Detail untuk beberapa tabel penting
echo "📋 DETAIL DATA\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Users by role
echo "👥 Users by Role:\n";
$usersByRole = User::select('role', DB::raw('count(*) as total'))
    ->groupBy('role')
    ->get();
foreach ($usersByRole as $item) {
    echo "   - " . str_pad(ucfirst($item->role), 15, ' ', STR_PAD_RIGHT) . ": " . $item->total . "\n";
}
echo "\n";

// Mahasiswa by status
echo "🎓 Mahasiswa by Status:\n";
$mahasiswaByStatus = Mahasiswa::select('status', DB::raw('count(*) as total'))
    ->groupBy('status')
    ->get();
foreach ($mahasiswaByStatus as $item) {
    echo "   - " . str_pad(ucfirst($item->status), 15, ' ', STR_PAD_RIGHT) . ": " . $item->total . "\n";
}
echo "\n";

// KRS by status
echo "📝 KRS by Status:\n";
$krsByStatus = Krs::select('status', DB::raw('count(*) as total'))
    ->groupBy('status')
    ->get();
foreach ($krsByStatus as $item) {
    echo "   - " . str_pad(ucfirst($item->status), 15, ' ', STR_PAD_RIGHT) . ": " . $item->total . "\n";
}
echo "\n";

// Pembayaran UKT by status
echo "💰 Pembayaran UKT by Status:\n";
$uktByStatus = PembayaranUkt::select('status', DB::raw('count(*) as total'))
    ->groupBy('status')
    ->get();
foreach ($uktByStatus as $item) {
    echo "   - " . str_pad(ucfirst($item->status), 15, ' ', STR_PAD_RIGHT) . ": " . $item->total . "\n";
}
echo "\n";

// Sample data
echo "📌 SAMPLE DATA (5 records):\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "👤 Users:\n";
$users = User::limit(5)->get(['id', 'name', 'email', 'role']);
foreach ($users as $user) {
    echo "   - {$user->name} ({$user->email}) - {$user->role}\n";
}
echo "\n";

echo "🎓 Mahasiswa:\n";
$mahasiswa = Mahasiswa::limit(5)->get(['id', 'nim', 'nama', 'prodi']);
foreach ($mahasiswa as $m) {
    echo "   - {$m->nim} - {$m->nama} ({$m->prodi})\n";
}
echo "\n";

echo "👨‍🏫 Dosen:\n";
$dosen = Dosen::limit(5)->get(['id', 'nidn', 'nama', 'prodi']);
foreach ($dosen as $d) {
    echo "   - {$d->nidn} - {$d->nama} ({$d->prodi})\n";
}
echo "\n";

echo "✅ Selesai!\n\n";
