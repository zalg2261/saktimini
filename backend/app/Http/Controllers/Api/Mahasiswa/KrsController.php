<?php

namespace App\Http\Controllers\Api\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\MataKuliah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KrsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = Krs::with(['mataKuliah' => function($q) {
            $q->with('dosen');
        }, 'mahasiswa']);

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

        if ($request->has('tahun_akademik')) {
            $query->where('tahun_akademik', $request->tahun_akademik);
        }

        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $perPage = min($request->get('per_page', 15), 100);
        
        try {
            $krs = $query->paginate($perPage);
            
            return response()->json([
                'data' => $krs->items(),
                'meta' => [
                    'current_page' => $krs->currentPage(),
                    'last_page' => $krs->lastPage(),
                    'per_page' => $krs->perPage(),
                    'total' => $krs->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('KRS Index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user' => $user->email,
                'role' => $userRole,
            ]);
            
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data KRS',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'tahun_akademik' => 'required|string',
            'semester' => 'required|integer|in:1,2',
        ]);

        $user = auth()->user();
        $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $existing = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('mata_kuliah_id', $request->mata_kuliah_id)
            ->where('tahun_akademik', $request->tahun_akademik)
            ->where('semester', $request->semester)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Mata kuliah sudah terdaftar di KRS'
            ], 422);
        }

        $krs = Krs::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'status' => 'pending',
        ]);

        return response()->json([
            'data' => $krs->load(['mataKuliah.dosen']),
            'message' => 'KRS berhasil dibuat'
        ], 201);
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

        $krs = Krs::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->with(['mataKuliah.dosen'])
            ->firstOrFail();

        return response()->json([
            'data' => $krs
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $user = auth()->user();
        $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $krs = Krs::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->firstOrFail();

        if ($krs->status !== 'pending') {
            return response()->json([
                'message' => 'KRS yang sudah disetujui tidak dapat dihapus'
            ], 422);
        }

        $krs->delete();

        return response()->json([
            'message' => 'KRS berhasil dihapus'
        ]);
    }

    public function availableMataKuliah(Request $request): JsonResponse
    {
        $user = auth()->user();
        $mahasiswa = \App\Models\Mahasiswa::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $query = MataKuliah::where('prodi', $mahasiswa->prodi)
            ->with('dosen');

            if ($request->has('semester')) {
                $query->where('semester', $request->semester);
            }
        if ($request->has('tahun_akademik') && $request->has('semester')) {
            $registeredIds = Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('tahun_akademik', $request->tahun_akademik)
                ->where('semester', $request->semester)
                ->pluck('mata_kuliah_id');
            
            $query->whereNotIn('id', $registeredIds);
        }

        $mataKuliah = $query->get();

        return response()->json([
            'data' => $mataKuliah
        ]);
    }
}
