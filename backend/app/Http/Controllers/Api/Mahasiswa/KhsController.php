<?php

namespace App\Http\Controllers\Api\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Khs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KhsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = Khs::with(['mataKuliah' => function($q) {
            $q->with('dosen');
        }, 'mahasiswa']);

        // If admin_pusat, allow filtering by mahasiswa_id or show all
        $userRole = is_object($user->role) ? $user->role->value : $user->role;
        if ($userRole === 'admin_pusat') {
            if ($request->has('mahasiswa_id')) {
                $query->where('mahasiswa_id', $request->mahasiswa_id);
            }
        } else {
            // For mahasiswa, only show their own KHS
            $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();
            if (!$mahasiswa) {
                return response()->json([
                    'message' => 'Data mahasiswa tidak ditemukan'
                ], 404);
            }
            $query->where('mahasiswa_id', $mahasiswa->id);
        }

        // Filter by tahun akademik
        if ($request->has('tahun_akademik')) {
            $query->where('tahun_akademik', $request->tahun_akademik);
        }

        // Filter by semester
        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        $perPage = min($request->get('per_page', 15), 100);
        
        try {
            $khs = $query->paginate($perPage);

            $totalSks = 0;
            $totalNilai = 0;
            foreach ($khs->items() as $item) {
                if ($item->nilai_akhir && $item->mataKuliah) {
                    $sks = $item->mataKuliah->sks;
                    $nilai = $this->hurufMutuToNilai($item->huruf_mutu);
                    $totalSks += $sks;
                    $totalNilai += $nilai * $sks;
                }
            }
            $ipk = $totalSks > 0 ? round($totalNilai / $totalSks, 2) : 0;

            return response()->json([
                'data' => $khs->items(),
                'meta' => [
                    'current_page' => $khs->currentPage(),
                    'last_page' => $khs->lastPage(),
                    'per_page' => $khs->perPage(),
                    'total' => $khs->total(),
                    'ipk' => $ipk,
                    'total_sks' => $totalSks,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('KHS Index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user' => $user->email,
                'role' => $userRole,
            ]);
            
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data KHS',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $user = auth()->user();
        $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $khs = Khs::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->with(['mataKuliah.dosen'])
            ->firstOrFail();

        return response()->json([
            'data' => $khs
        ]);
    }

    private function hurufMutuToNilai(?string $hurufMutu): float
    {
        return match($hurufMutu) {
            'A' => 4.0,
            'A-' => 3.75,
            'B+' => 3.5,
            'B' => 3.0,
            'B-' => 2.75,
            'C+' => 2.5,
            'C' => 2.0,
            'D' => 1.0,
            'E' => 0.0,
            default => 0.0,
        };
    }
}
