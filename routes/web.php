<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParticipantCrudController;

// --- DOMAIN PESERTA ---
Route::domain('sertifikat.majuterus.my.id')->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('home');
    Route::get('/api/check-nim/{nim}', [GuestController::class, 'checkNim']); 
    Route::post('/claim', [GuestController::class, 'processClaim'])->name('claim.process');
    Route::get('/download-file/{token}/{id}', [GuestController::class, 'downloadByToken'])->name('download.token');

    // --- FORM REGISTRASI MANDIRI (PUBLIK) ---
    Route::get('/register', [GuestController::class, 'showRegister'])->name('register.show');
    Route::post('/register', [GuestController::class, 'processRegister'])->name('register.process');
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
        
        Route::post('/open-session', [AdminController::class, 'openSession'])->name('admin.open-session');
        Route::post('/close-session', [AdminController::class, 'closeSession'])->name('admin.close-session');
        Route::post('/save-prefix', [AdminController::class, 'savePrefix'])->name('admin.save-prefix');
        Route::post('/save-seminar-title', [AdminController::class, 'saveSeminarTitle'])->name('admin.save-seminar-title');

        // --- MANAJEMEN PESERTA (CRUD) ---
        Route::get('/participants', [ParticipantCrudController::class, 'index'])->name('admin.participants.index');
        Route::post('/participants', [ParticipantCrudController::class, 'store'])->name('admin.participants.store');
        Route::put('/participants/{id}', [ParticipantCrudController::class, 'update'])->name('admin.participants.update');
        Route::delete('/participants/{id}', [ParticipantCrudController::class, 'destroy'])->name('admin.participants.destroy');

        // --- PENGATURAN REGISTRASI PUBLIK ---
        Route::get('/registration-setting', [ParticipantCrudController::class, 'registrationSetting'])->name('admin.registration.setting');
        Route::post('/registration-setting/toggle', [ParticipantCrudController::class, 'toggleRegistration'])->name('admin.registration.toggle');

        Route::middleware('can:is-administrator')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('admin.users');
            Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });
    });
});