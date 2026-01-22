<?php

namespace App\Http\Controllers\Api\AdminPusat;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    /**
     * Display a listing of dosen
     */
    public function index(Request $request): JsonResponse
    {
        $query = Dosen::with('mataKuliah');

        // Search
        if ($request->has('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'ilike', "%{$searchTerm}%")
                  ->orWhere('nidn', 'ilike', "%{$searchTerm}%")
                  ->orWhere('email', 'ilike', "%{$searchTerm}%");
            });
        }

        // Filter by prodi
        if ($request->has('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        // Filter by jabatan
        if ($request->has('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'nama');
        $sortDir = $request->get('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = min($request->get('per_page', 15), 100);
        $dosen = $query->paginate($perPage);

        return response()->json([
            'data' => $dosen->items(),
            'meta' => [
                'current_page' => $dosen->currentPage(),
                'last_page' => $dosen->lastPage(),
                'per_page' => $dosen->perPage(),
                'total' => $dosen->total(),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nidn' => 'required|string|unique:dosen,nidn',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosen,email|unique:users,email',
            'prodi' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dosen',
        ]);

        $dosen = Dosen::create([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'email' => $request->email,
            'prodi' => $request->prodi,
            'jabatan' => $request->jabatan,
        ]);

        return response()->json([
            'data' => $dosen->load('mataKuliah'),
            'message' => 'Dosen berhasil dibuat'
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $dosen = Dosen::with('mataKuliah')->findOrFail($id);

        return response()->json([
            'data' => $dosen
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nidn' => ['required', 'string', Rule::unique('dosen', 'nidn')->ignore($id)],
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('dosen', 'email')->ignore($id), Rule::unique('users', 'email')->ignore($dosen->email, 'email')],
            'prodi' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::where('email', $dosen->email)->first();
        if ($user) {
            $user->update([
                'name' => $request->nama,
                'email' => $request->email,
            ]);

            if ($request->has('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        }

        $dosen->update([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'email' => $request->email,
            'prodi' => $request->prodi,
            'jabatan' => $request->jabatan,
        ]);

        return response()->json([
            'data' => $dosen->load('mataKuliah'),
            'message' => 'Dosen berhasil diperbarui'
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return response()->json([
            'message' => 'Dosen berhasil dihapus'
        ]);
    }
}
