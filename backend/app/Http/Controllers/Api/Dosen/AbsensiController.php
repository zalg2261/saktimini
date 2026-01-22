<?php

namespace App\Http\Controllers\Api\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\MataKuliah;
use App\Models\Presensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    private function getDosen()
    {
        $user = auth()->user();
        return \App\Models\Dosen::where('email', $user->email)->first();
    }

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = Absensi::with(['mataKuliah']);

        $userRole = is_object($user->role) ? $user->role->value : $user->role;
        if ($userRole === 'admin_pusat') {
            if ($request->has('dosen_id')) {
                $query->where('dosen_id', $request->dosen_id);
            }
        } else {
            $dosen = $this->getDosen();
            if (!$dosen) {
                return response()->json([
                    'message' => 'Data dosen tidak ditemukan'
                ], 404);
            }
            $query->where('dosen_id', $dosen->id);
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

        $query->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc');

        $perPage = min($request->get('per_page', 15), 100);
        $absensi = $query->paginate($perPage);

        return response()->json([
            'data' => $absensi->items(),
            'meta' => [
                'current_page' => $absensi->currentPage(),
                'last_page' => $absensi->lastPage(),
                'per_page' => $absensi->perPage(),
                'total' => $absensi->total(),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $dosen = $this->getDosen();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);
        if ($mataKuliah->dosen_id !== $dosen->id) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses ke mata kuliah ini'
            ], 403);
        }

        $absensi = Absensi::create([
            'dosen_id' => $dosen->id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'topik' => $request->topik,
            'catatan' => $request->catatan,
        ]);

        return response()->json([
            'data' => $absensi->load(['mataKuliah']),
            'message' => 'Sesi absensi berhasil dibuat'
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'tanggal' => 'sometimes|required|date',
            'jam_mulai' => 'sometimes|required|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'topik' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $dosen = $this->getDosen();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $absensi = Absensi::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        $absensi->update($request->only([
            'tanggal', 'jam_mulai', 'jam_selesai', 'topik', 'catatan'
        ]));

        return response()->json([
            'data' => $absensi->load(['mataKuliah']),
            'message' => 'Sesi absensi berhasil diperbarui'
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $user = auth()->user();
        $query = Absensi::with(['mataKuliah']);

        $userRole = is_object($user->role) ? $user->role->value : $user->role;
        if ($userRole !== 'admin_pusat') {
            $dosen = $this->getDosen();
            if (!$dosen) {
                return response()->json([
                    'message' => 'Data dosen tidak ditemukan'
                ], 404);
            }
            $query->where('dosen_id', $dosen->id);
        }

        $absensi = $query->where('id', $id)->firstOrFail();

        $presensi = Presensi::where('mata_kuliah_id', $absensi->mata_kuliah_id)
            ->where('tanggal', $absensi->tanggal)
            ->with(['mahasiswa'])
            ->get();

        return response()->json([
            'data' => [
                'absensi' => $absensi,
                'presensi' => $presensi,
            ]
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $dosen = $this->getDosen();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $absensi = Absensi::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        $absensi->delete();

        return response()->json([
            'message' => 'Sesi absensi berhasil dihapus'
        ]);
    }

    public function recordPresensi(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'presensi' => 'required|array',
            'presensi.*.mahasiswa_id' => 'required|exists:mahasiswa,id',
            'presensi.*.status' => 'required|in:hadir,izin,sakit,alpha',
            'presensi.*.keterangan' => 'nullable|string',
        ]);

        $dosen = $this->getDosen();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $absensi = Absensi::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        $created = [];
        foreach ($request->presensi as $item) {
            $presensi = Presensi::updateOrCreate(
                [
                    'mahasiswa_id' => $item['mahasiswa_id'],
                    'mata_kuliah_id' => $absensi->mata_kuliah_id,
                    'tanggal' => $absensi->tanggal,
                ],
                [
                    'jam_mulai' => $absensi->jam_mulai,
                    'jam_selesai' => $absensi->jam_selesai,
                    'status' => $item['status'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]
            );
            $created[] = $presensi->load('mahasiswa');
        }

        return response()->json([
            'data' => $created,
            'message' => 'Presensi berhasil direkam'
        ]);
    }
}
