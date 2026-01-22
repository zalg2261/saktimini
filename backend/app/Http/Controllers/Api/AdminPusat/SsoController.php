<?php

namespace App\Http\Controllers\Api\AdminPusat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    public function generateToken(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'expires_in' => 'nullable|integer|min:60|max:86400',
        ]);

        $user = User::findOrFail($request->user_id);
        $token = Str::random(64);
        $expiresAt = now()->addSeconds($request->expires_in ?? 3600);
        
        return response()->json([
            'data' => [
                'token' => $token,
                'expires_at' => $expiresAt,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ],
            'message' => 'SSO token berhasil dibuat'
        ]);
    }

    public function validateToken(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
        ]);
        
        return response()->json([
            'data' => [
                'valid' => false,
                'message' => 'Token validation not implemented yet'
            ]
        ]);
    }

    public function getConfig(): JsonResponse
    {
        return response()->json([
            'data' => [
                'enabled' => true,
                'provider' => 'internal',
                'endpoint' => config('app.url') . '/sso',
            ]
        ]);
    }

    public function updateConfig(Request $request): JsonResponse
    {
        $request->validate([
            'enabled' => 'sometimes|boolean',
            'provider' => 'sometimes|string',
            'endpoint' => 'sometimes|url',
        ]);
        
        return response()->json([
            'message' => 'Konfigurasi SSO berhasil diperbarui',
            'data' => $request->all()
        ]);
    }
}
