<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;

// --- DOMAIN PESERTA ---
Route::domain('sertifikat.majuterus.my.id')->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('home');
    
    // Rute Link Unduh Dinamis
    Route::get('/claim/{code}', [GuestController::class, 'showClaimForm'])->name('claim.form');
    Route::post('/claim/{code}', [GuestController::class, 'processClaim']);
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
        
        // Rute Generator Link Dinamis
        Route::post('/generate-link', [AdminController::class, 'generateLink'])->name('admin.generate-link');
        Route::post('/close-link', [AdminController::class, 'closeLink'])->name('admin.close-link');

        Route::post('/save-prefix', [AdminController::class, 'savePrefix'])->name('admin.save-prefix');

        Route::middleware('can:is-administrator')->group(function () {
            Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users');
            Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
            Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');
        });
    });
});