<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\Mahasiswa\KrsController as MahasiswaKrsController;
use App\Http\Controllers\Api\Mahasiswa\KhsController as MahasiswaKhsController;
use App\Http\Controllers\Api\Mahasiswa\PresensiController as MahasiswaPresensiController;
use App\Http\Controllers\Api\Dosen\PenilaianController as DosenPenilaianController;
use App\Http\Controllers\Api\Dosen\MateriController as DosenMateriController;
use App\Http\Controllers\Api\Dosen\AbsensiController as DosenAbsensiController;
use App\Http\Controllers\Api\AdminPusat\PddiktiController;
use App\Http\Controllers\Api\AdminPusat\UktController;
use App\Http\Controllers\Api\AdminPusat\SsoController;
use App\Http\Controllers\Api\AdminPusat\DosenController as AdminPusatDosenController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth:api'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Mahasiswa features (Mahasiswa role only) - MUST BE BEFORE mahasiswa CRUD to avoid route conflict
    Route::middleware(['role:mahasiswa,admin_pusat'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/krs/available-mata-kuliah', [MahasiswaKrsController::class, 'availableMataKuliah']);
        Route::apiResource('krs', MahasiswaKrsController::class);
        Route::apiResource('khs', MahasiswaKhsController::class)->only(['index', 'show']);
        Route::apiResource('presensi', MahasiswaPresensiController::class)->only(['index', 'show']);
    });
    
    // Mahasiswa CRUD (Admin Prodi & Admin Pusat) - MUST BE AFTER mahasiswa features routes
    Route::middleware(['role:admin_prodi,admin_pusat'])->group(function () {
        Route::apiResource('mahasiswa', MahasiswaController::class);
    });
    
    // Dosen features (Dosen role only)
    Route::middleware(['role:dosen,admin_pusat'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::apiResource('penilaian', DosenPenilaianController::class);
        Route::apiResource('materi', DosenMateriController::class);
        Route::post('absensi/{id}/record-presensi', [DosenAbsensiController::class, 'recordPresensi']);
        Route::apiResource('absensi', DosenAbsensiController::class);
    });
    
    // Admin Pusat features
    Route::middleware(['role:admin_pusat'])->prefix('admin-pusat')->name('admin-pusat.')->group(function () {
        // Dosen CRUD
        Route::apiResource('dosen', AdminPusatDosenController::class);
        
        // PDDIKTI
        Route::post('/pddikti/sync/mahasiswa', [PddiktiController::class, 'syncMahasiswa']);
        Route::post('/pddikti/sync/all-mahasiswa', [PddiktiController::class, 'syncAllMahasiswa']);
        Route::post('/pddikti/sync/dosen', [PddiktiController::class, 'syncDosen']);
        Route::get('/pddikti/sync/status', [PddiktiController::class, 'syncStatus']);
        
        // UKT
        Route::apiResource('ukt', UktController::class);
        
        // SSO
        Route::post('/sso/generate-token', [SsoController::class, 'generateToken']);
        Route::post('/sso/validate-token', [SsoController::class, 'validateToken']);
        Route::get('/sso/config', [SsoController::class, 'getConfig']);
        Route::put('/sso/config', [SsoController::class, 'updateConfig']);
    });
});
