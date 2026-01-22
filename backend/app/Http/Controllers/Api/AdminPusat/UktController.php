<?php

namespace App\Http\Controllers\Api\AdminPusat;

use App\Http\Controllers\Controller;
use App\Models\PembayaranUkt;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UktController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PembayaranUkt::with(['mahasiswa']);

        if ($request->has('q')) {
            $searchTerm = $request->q;
            $mahasiswaIds = Mahasiswa::where('nama', 'ilike', "%{$searchTerm}%")
                ->orWhere('nim', 'ilike', "%{$searchTerm}%")
                ->pluck('id');
            
            $query->whereIn('mahasiswa_id', $mahasiswaIds);
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

        if ($request->has('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        $query->orderBy('created_at', 'desc');

        $perPage = min($request->get('per_page', 15), 100);
        $pembayaran = $query->paginate($perPage);

        return response()->json([
            'data' => $pembayaran->items(),
            'meta' => [
                'current_page' => $pembayaran->currentPage(),
                'last_page' => $pembayaran->lastPage(),
                'per_page' => $pembayaran->perPage(),
                'total' => $pembayaran->total(),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'tahun_akademik' => 'required|string',
            'semester' => 'required|integer|in:1,2',
            'jumlah' => 'required|numeric|min:0',
            'metode_pembayaran' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $pembayaran = PembayaranUkt::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
            'metode_pembayaran' => $request->metode_pembayaran,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'data' => $pembayaran->load(['mahasiswa']),
            'message' => 'Pembayaran UKT berhasil dibuat'
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'status' => 'sometimes|required|in:pending,lunas,tertunda,dibatalkan',
            'tanggal_bayar' => 'nullable|date|required_if:status,lunas',
            'bukti_pembayaran' => 'nullable|file|max:5120',
            'metode_pembayaran' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $pembayaran = PembayaranUkt::findOrFail($id);

        if ($request->hasFile('bukti_pembayaran')) {
            if ($pembayaran->bukti_pembayaran && Storage::disk('public')->exists($pembayaran->bukti_pembayaran)) {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
            }

            $file = $request->file('bukti_pembayaran');
            $pembayaran->bukti_pembayaran = $file->store('bukti-pembayaran', 'public');
        }

        $pembayaran->update([
            'status' => $request->status ?? $pembayaran->status,
            'tanggal_bayar' => $request->tanggal_bayar ?? $pembayaran->tanggal_bayar,
            'metode_pembayaran' => $request->metode_pembayaran ?? $pembayaran->metode_pembayaran,
            'keterangan' => $request->keterangan ?? $pembayaran->keterangan,
        ]);

        return response()->json([
            'data' => $pembayaran->load(['mahasiswa']),
            'message' => 'Pembayaran UKT berhasil diperbarui'
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $pembayaran = PembayaranUkt::with(['mahasiswa'])->findOrFail($id);

        return response()->json([
            'data' => $pembayaran
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $pembayaran = PembayaranUkt::findOrFail($id);

        if ($pembayaran->bukti_pembayaran && Storage::disk('public')->exists($pembayaran->bukti_pembayaran)) {
            Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
        }

        $pembayaran->delete();

        return response()->json([
            'message' => 'Pembayaran UKT berhasil dihapus'
        ]);
    }
}
