<?php

namespace App\Http\Controllers\Api\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresensiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = Presensi::with(['mataKuliah' => function($q) {
            $q->with('dosen');
        }, 'mahasiswa']);
        $mahasiswa = null;

        $userRole = is_object($user->role) ? $user->role->value : $user->role;
        if ($userRole === 'admin_pusat') {
            if ($request->has('mahasiswa_id')) {
                $query->where('mahasiswa_id', $request->mahasiswa_id);
            }
        } else {
            $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();
            if (!$mahasiswa) {
                return response()->json([
                    'message' => 'Data mahasiswa tidak ditemukan'
                ], 404);
            }
            $query->where('mahasiswa_id', $mahasiswa->id);
        }

        if ($request->has('mata_kuliah_id')) {
            $query->where('mata_kuliah_id', $request->mata_kuliah_id);
        }

        if ($request->has('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc');

        $perPage = min($request->get('per_page', 15), 100);
        
        try {
            $presensi = $query->paginate($perPage);

            $statsQuery = Presensi::query();
            if ($userRole !== 'admin_pusat' && $mahasiswa) {
                $statsQuery->where('mahasiswa_id', $mahasiswa->id);
            } elseif ($userRole === 'admin_pusat' && $request->has('mahasiswa_id')) {
                $statsQuery->where('mahasiswa_id', $request->mahasiswa_id);
            }
            
            $total = $statsQuery->when($request->has('mata_kuliah_id'), function($q) use ($request) {
                    $q->where('mata_kuliah_id', $request->mata_kuliah_id);
                })
                ->count();
            
            $hadirQuery = Presensi::query();
            if ($userRole !== 'admin_pusat' && $mahasiswa) {
                $hadirQuery->where('mahasiswa_id', $mahasiswa->id);
            } elseif ($userRole === 'admin_pusat' && $request->has('mahasiswa_id')) {
                $hadirQuery->where('mahasiswa_id', $request->mahasiswa_id);
            }
            
            $hadir = $hadirQuery->where('status', 'hadir')
                ->when($request->has('mata_kuliah_id'), function($q) use ($request) {
                    $q->where('mata_kuliah_id', $request->mata_kuliah_id);
                })
                ->count();

            return response()->json([
                'data' => $presensi->items(),
                'meta' => [
                    'current_page' => $presensi->currentPage(),
                    'last_page' => $presensi->lastPage(),
                    'per_page' => $presensi->perPage(),
                    'total' => $presensi->total(),
                    'statistik' => [
                        'total' => $total,
                        'hadir' => $hadir,
                        'persentase_hadir' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0,
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Presensi Index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user' => $user->email,
                'role' => $userRole,
            ]);
            
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data Presensi',
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

        $presensi = Presensi::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->with(['mataKuliah.dosen'])
            ->firstOrFail();

        return response()->json([
            'data' => $presensi
        ]);
    }
}
