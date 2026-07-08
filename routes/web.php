<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;

// --- DOMAIN PESERTA ---
Route::domain('sertifikat.majuterus.my.id')->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('home');
    Route::get('/api/check-nim/{nim}', [GuestController::class, 'checkNim']);
    Route::get('/download-sertifikat/{nim}', [GuestController::class, 'download'])->name('download');
    
    // Rute Check-in Mandiri dengan Kode Dinamis
    Route::get('/checkin/{code}', [GuestController::class, 'showCheckin'])->name('checkin');
    Route::post('/checkin/{code}', [GuestController::class, 'processCheckin']);
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
        
        // Rute Manajemen Link Check-in
        Route::post('/generate-link', [AdminController::class, 'generateLink'])->name('admin.generate-link');
        Route::post('/close-link', [AdminController::class, 'closeLink'])->name('admin.close-link');
    });
});