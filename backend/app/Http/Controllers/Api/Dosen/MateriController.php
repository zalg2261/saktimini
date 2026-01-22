<?php

namespace App\Http\Controllers\Api\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\MataKuliah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Get dosen from authenticated user
     */
    private function getDosen()
    {
        $user = auth()->user();
        return \App\Models\Dosen::where('email', $user->email)->first();
    }

    /**
     * Display a listing of materi
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = Materi::with(['mataKuliah']);

        // If admin_pusat, allow filtering by dosen_id or show all
        $userRole = is_object($user->role) ? $user->role->value : $user->role;
        if ($userRole === 'admin_pusat') {
            if ($request->has('dosen_id')) {
                $query->where('dosen_id', $request->dosen_id);
            }
        } else {
            // For dosen, only show their own materi
            $dosen = $this->getDosen();
            if (!$dosen) {
                return response()->json([
                    'message' => 'Data dosen tidak ditemukan'
                ], 404);
            }
            $query->where('dosen_id', $dosen->id);
        }

        // Filter by mata kuliah
        if ($request->has('mata_kuliah_id')) {
            $query->where('mata_kuliah_id', $request->mata_kuliah_id);
        }

        // Search
        if ($request->has('q')) {
            $query->where('judul', 'ilike', "%{$request->q}%");
        }

        $perPage = min($request->get('per_page', 15), 100);
        $materi = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'data' => $materi->items(),
            'meta' => [
                'current_page' => $materi->currentPage(),
                'last_page' => $materi->lastPage(),
                'per_page' => $materi->perPage(),
                'total' => $materi->total(),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'tipe' => 'required|in:pdf,doc,ppt,video,link',
            'link' => 'nullable|url|required_if:tipe,link',
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

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('materi', 'public');
        } elseif ($request->tipe === 'link') {
            $filePath = $request->link;
        }

        $materi = Materi::create([
            'dosen_id' => $dosen->id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'tipe' => $request->tipe,
        ]);

        return response()->json([
            'data' => $materi->load(['mataKuliah']),
            'message' => 'Materi berhasil dibuat'
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'link' => 'nullable|url',
        ]);

        $dosen = $this->getDosen();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $materi = Materi::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        if ($request->hasFile('file')) {
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $file = $request->file('file');
            $materi->file_name = $file->getClientOriginalName();
            $materi->file_path = $file->store('materi', 'public');
        } elseif ($request->has('link') && $materi->tipe === 'link') {
            $materi->file_path = $request->link;
        }

        $materi->update([
            'judul' => $request->judul ?? $materi->judul,
            'deskripsi' => $request->deskripsi ?? $materi->deskripsi,
        ]);

        return response()->json([
            'data' => $materi->load(['mataKuliah']),
            'message' => 'Materi berhasil diperbarui'
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $user = auth()->user();
        $query = Materi::with(['mataKuliah']);

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

        $materi = $query->where('id', $id)->firstOrFail();

        return response()->json([
            'data' => $materi
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

        $materi = Materi::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return response()->json([
            'message' => 'Materi berhasil dihapus'
        ]);
    }
}
