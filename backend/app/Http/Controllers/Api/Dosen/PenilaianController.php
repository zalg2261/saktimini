<?php

namespace App\Http\Controllers\Api\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    private function getDosen()
    {
        $user = auth()->user();
        return \App\Models\Dosen::where('email', $user->email)->first();
    }

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = Penilaian::with(['mahasiswa', 'mataKuliah']);

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

        if ($request->has('tahun_akademik')) {
            $query->where('tahun_akademik', $request->tahun_akademik);
        }

        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('q')) {
            $searchTerm = $request->q;
            $mahasiswaIds = Mahasiswa::where('nama', 'ilike', "%{$searchTerm}%")
                ->orWhere('nim', 'ilike', "%{$searchTerm}%")
                ->pluck('id');
            
            $query->whereIn('mahasiswa_id', $mahasiswaIds);
        }

        $perPage = min($request->get('per_page', 15), 100);
        $penilaian = $query->paginate($perPage);

        return response()->json([
            'data' => $penilaian->items(),
            'meta' => [
                'current_page' => $penilaian->currentPage(),
                'last_page' => $penilaian->lastPage(),
                'per_page' => $penilaian->perPage(),
                'total' => $penilaian->total(),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'tahun_akademik' => 'required|string',
            'semester' => 'required|integer|in:1,2',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_kehadiran' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $dosen = $this->getDosen();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        // Check if mata kuliah belongs to dosen
        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);
        if ($mataKuliah->dosen_id !== $dosen->id) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses ke mata kuliah ini'
            ], 403);
        }

        $nilaiAkhir = $this->calculateNilaiAkhir(
            $request->nilai_uts,
            $request->nilai_uas,
            $request->nilai_tugas,
            $request->nilai_kehadiran
        );

        $hurufMutu = $this->nilaiToHurufMutu($nilaiAkhir);

        $penilaian = Penilaian::create([
            'dosen_id' => $dosen->id,
            'mahasiswa_id' => $request->mahasiswa_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_kehadiran' => $request->nilai_kehadiran,
            'nilai_akhir' => $nilaiAkhir,
            'huruf_mutu' => $hurufMutu,
            'catatan' => $request->catatan,
        ]);

        $this->updateKhs($penilaian);

        return response()->json([
            'data' => $penilaian->load(['mahasiswa', 'mataKuliah']),
            'message' => 'Penilaian berhasil dibuat'
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_kehadiran' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $dosen = $this->getDosen();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $penilaian = Penilaian::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        $nilaiAkhir = $this->calculateNilaiAkhir(
            $request->nilai_uts ?? $penilaian->nilai_uts,
            $request->nilai_uas ?? $penilaian->nilai_uas,
            $request->nilai_tugas ?? $penilaian->nilai_tugas,
            $request->nilai_kehadiran ?? $penilaian->nilai_kehadiran
        );

        $hurufMutu = $this->nilaiToHurufMutu($nilaiAkhir);

        $penilaian->update([
            'nilai_uts' => $request->nilai_uts ?? $penilaian->nilai_uts,
            'nilai_uas' => $request->nilai_uas ?? $penilaian->nilai_uas,
            'nilai_tugas' => $request->nilai_tugas ?? $penilaian->nilai_tugas,
            'nilai_kehadiran' => $request->nilai_kehadiran ?? $penilaian->nilai_kehadiran,
            'nilai_akhir' => $nilaiAkhir,
            'huruf_mutu' => $hurufMutu,
            'komentar' => $request->komentar ?? $penilaian->komentar,
        ]);

        $this->updateKhs($penilaian);

        return response()->json([
            'data' => $penilaian->load(['mahasiswa', 'mataKuliah']),
            'message' => 'Penilaian berhasil diperbarui'
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $dosen = $this->getDosen();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $penilaian = Penilaian::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->with(['mahasiswa', 'mataKuliah'])
            ->firstOrFail();

        return response()->json([
            'data' => $penilaian
        ]);
    }

    private function calculateNilaiAkhir($uts, $uas, $tugas, $kehadiran): ?float
    {
        $values = array_filter([$uts, $uas, $tugas, $kehadiran], fn($v) => $v !== null);
        
        if (empty($values)) {
            return null;
        }

        // Weight: UTS 30%, UAS 40%, Tugas 20%, Kehadiran 10%
        $utsWeight = $uts ? ($uts * 0.3) : 0;
        $uasWeight = $uas ? ($uas * 0.4) : 0;
        $tugasWeight = $tugas ? ($tugas * 0.2) : 0;
        $kehadiranWeight = $kehadiran ? ($kehadiran * 0.1) : 0;

        return round($utsWeight + $uasWeight + $tugasWeight + $kehadiranWeight, 2);
    }

    private function nilaiToHurufMutu(?float $nilai): ?string
    {
        if ($nilai === null) {
            return null;
        }

        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 80 => 'B+',
            $nilai >= 75 => 'B',
            $nilai >= 70 => 'C+',
            $nilai >= 65 => 'C',
            $nilai >= 50 => 'D',
            default => 'E',
        };
    }

    private function updateKhs(Penilaian $penilaian): void
    {
        \App\Models\Khs::updateOrCreate(
            [
                'mahasiswa_id' => $penilaian->mahasiswa_id,
                'mata_kuliah_id' => $penilaian->mata_kuliah_id,
                'tahun_akademik' => $penilaian->tahun_akademik,
                'semester' => $penilaian->semester,
            ],
            [
                'nilai_uts' => $penilaian->nilai_uts,
                'nilai_uas' => $penilaian->nilai_uas,
                'nilai_tugas' => $penilaian->nilai_tugas,
                'nilai_akhir' => $penilaian->nilai_akhir,
                'huruf_mutu' => $penilaian->huruf_mutu,
            ]
        );
    }
}
