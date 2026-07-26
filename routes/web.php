<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// --- DOMAIN PESERTA ---
Route::domain('sertifikat.majuterus.my.id')->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('home');
    
    // Rute API untuk cek nama real-time via AJAX (JavaScript)
    Route::get('/api/check-nim/{nim}', [GuestController::class, 'checkNim']); 
    
    // Rute proses unduh dengan sistem token aman
    Route::post('/claim', [GuestController::class, 'processClaim'])->name('claim.process');
    Route::get('/download-file/{token}/{id}', [GuestController::class, 'downloadByToken'])->name('download.token');
});

// --- DOMAIN ADMIN ---
Route::domain('han.majuterus.my.id')->group(function () {
    Route::get('/', function () { return redirect()->route('login'); });
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    Route::middleware('auth')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/upload-data', [AdminController::class, 'uploadData'])->name('admin.upload-data');
        Route::post('/upload-template', [AdminController::class, 'uploadTemplate'])->name('admin.upload-template');
        Route::post('/clear-data', [AdminController::class, 'clearData'])->name('admin.clear-data');
        Route::post('/clear-template', [AdminController::class, 'clearTemplate'])->name('admin.clear-template');
        
        // Rute Sistem Buka/Tutup Portal (ON/OFF)
        Route::post('/open-session', [AdminController::class, 'openSession'])->name('admin.open-session');
        Route::post('/close-session', [AdminController::class, 'closeSession'])->name('admin.close-session');
        
        Route::post('/save-prefix', [AdminController::class, 'savePrefix'])->name('admin.save-prefix');

        Route::middleware('can:is-administrator')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('admin.users');
            Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });
    });
});