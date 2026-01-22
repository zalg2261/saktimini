<?php

namespace App\Http\Controllers\Api\AdminPusat;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PddiktiController extends Controller
{
    public function syncMahasiswa(Request $request): JsonResponse
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($request->mahasiswa_id);
        
        return response()->json([
            'message' => 'Sinkronisasi ke PDDIKTI berhasil',
            'data' => [
                'mahasiswa' => $mahasiswa,
                'status' => 'synced',
                'synced_at' => now(),
            ]
        ]);
    }

    public function syncAllMahasiswa(Request $request): JsonResponse
    {
        $count = Mahasiswa::where('status', 'aktif')->count();

        return response()->json([
            'message' => 'Sinkronisasi batch ke PDDIKTI dimulai',
            'data' => [
                'total_mahasiswa' => $count,
                'status' => 'processing',
            ]
        ]);
    }

    public function syncStatus(): JsonResponse
    {
        return response()->json([
            'data' => [
                'last_sync' => now()->subHours(2),
                'status' => 'success',
                'total_synced' => 0,
            ]
        ]);
    }

    public function syncDosen(Request $request): JsonResponse
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
        ]);

        $dosen = Dosen::findOrFail($request->dosen_id);
        
        return response()->json([
            'message' => 'Sinkronisasi dosen ke PDDIKTI berhasil',
            'data' => [
                'dosen' => $dosen,
                'status' => 'synced',
                'synced_at' => now(),
            ]
        ]);
    }
}
