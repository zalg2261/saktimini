<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $key = 'login.' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.'
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $user = User::where('email', $request->email)->first();
        $errorMessage = 'Email atau password salah.';

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => $errorMessage
            ], 401);
        }

        $token = JWTAuth::fromUser($user);
        RateLimiter::clear($key);

        $isLocalhost = str_contains(config('app.url'), 'localhost') || str_contains(config('app.url'), '127.0.0.1');
        
        $cookie = cookie(
            'jwt_token',
            $token,
            config('jwt.ttl') * 60,
            '/',
            $isLocalhost ? null : config('app.cookie_domain', '.kampus.ac.id'),
            !$isLocalhost,
            true,
            false,
            $isLocalhost ? 'lax' : 'none'
        );

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ],
            'message' => 'Login berhasil'
        ])->cookie($cookie);
    }

    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Exception $e) {
        }

        $isLocalhost = str_contains(config('app.url'), 'localhost') || str_contains(config('app.url'), '127.0.0.1');
        $cookie = cookie()->forget('jwt_token', '/', $isLocalhost ? null : config('app.cookie_domain', '.kampus.ac.id'));

        return response()->json([
            'message' => 'Logout berhasil'
        ])->cookie($cookie);
    }

    public function profile(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]
        ]);
    }
}
