<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Mahasiswa::query();

        if ($request->has('q') && $request->q) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nim', 'ilike', "%{$searchTerm}%")
                  ->orWhere('nama', 'ilike', "%{$searchTerm}%")
                  ->orWhere('email', 'ilike', "%{$searchTerm}%");
            });
        }

        if ($request->has('prodi') && $request->prodi) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('angkatan') && $request->angkatan) {
            $query->where('angkatan', $request->angkatan);
        }

        $sortBy = $request->get('sortBy', 'created_at');
        $sortDir = $request->get('sortDir', 'desc');
        
        $allowedSortFields = ['id', 'nim', 'nama', 'email', 'prodi', 'angkatan', 'status', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDir === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = min($request->get('per_page', 15), 100);
        $mahasiswa = $query->paginate($perPage);

        return response()->json([
            'data' => $mahasiswa->items(),
            'meta' => [
                'current_page' => $mahasiswa->currentPage(),
                'last_page' => $mahasiswa->lastPage(),
                'per_page' => $mahasiswa->perPage(),
                'total' => $mahasiswa->total(),
                'from' => $mahasiswa->firstItem(),
                'to' => $mahasiswa->lastItem(),
            ]
        ]);
    }

    public function store(StoreMahasiswaRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $password = $validated['password'];
        unset($validated['password']);

        User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => 'mahasiswa',
        ]);

        $mahasiswa = Mahasiswa::create($validated);

        return response()->json([
            'data' => $mahasiswa,
            'message' => 'Mahasiswa berhasil ditambahkan'
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        return response()->json([
            'data' => $mahasiswa
        ]);
    }

    public function update(UpdateMahasiswaRequest $request, string $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $validated = $request->validated();
        $password = $validated['password'] ?? null;
        unset($validated['password']);

        $user = User::where('email', $mahasiswa->email)->first();
        if ($user) {
            $user->update([
                'name' => $validated['nama'],
                'email' => $validated['email'],
            ]);

            if ($password) {
                $user->update([
                    'password' => Hash::make($password),
                ]);
            }
        }

        $mahasiswa->update($validated);

        return response()->json([
            'data' => $mahasiswa,
            'message' => 'Mahasiswa berhasil diperbarui'
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return response()->json([
            'message' => 'Mahasiswa berhasil dihapus'
        ]);
    }
}
