<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;

// Rute Tamu (Guest)
Route::get('/', [GuestController::class, 'index'])->name('home');
Route::get('/api/check-nim/{nim}', [GuestController::class, 'checkNim']);
Route::get('/download-sertifikat/{nim}', [GuestController::class, 'download'])->name('download');

// Rute Login Admin
Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminController::class, 'login']);
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// Rute Dashboard Admin (Hanya bisa diakses jika sudah login)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/upload-data', [AdminController::class, 'uploadData'])->name('admin.upload-data');
    Route::post('/upload-template', [AdminController::class, 'uploadTemplate'])->name('admin.upload-template');
});