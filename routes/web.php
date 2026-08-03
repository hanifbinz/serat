<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ParticipantCrudController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\RegistrationSettingController;

// --- AREA PUBLIK (PESERTA) ---
Route::get('/', function () {
    return redirect()->route('guest.register');
});

// Pendaftaran & Background
Route::get('/register', [GuestController::class, 'showRegistrationForm'])->name('guest.register');
Route::post('/register', [GuestController::class, 'submitRegistration'])->name('guest.register.submit');
Route::get('/download-background', [GuestController::class, 'downloadBackground'])->name('guest.download.background');

// Portal Sertifikat
Route::get('/sertifikat', [GuestController::class, 'searchCertificate'])->name('guest.certificate.search');
Route::post('/sertifikat/download', [GuestController::class, 'downloadCertificate'])->name('guest.certificate.download');


// --- AREA ADMIN ---
// Asumsi menggunakan middleware auth bawaan Laravel (Breeze/UI)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Dashboard (Galeri/Dokumentasi)
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // 2. Kelola Sertifikat
    Route::get('/certificate', [CertificateController::class, 'index'])->name('certificate.index');
    Route::post('/certificate/upload', [CertificateController::class, 'uploadTemplate'])->name('certificate.upload');
    Route::post('/certificate/toggle', [CertificateController::class, 'togglePortal'])->name('certificate.toggle');
    Route::post('/certificate/settings', [CertificateController::class, 'updateSettings'])->name('certificate.settings');

    // 3. Data Peserta (CRUD + CSV + Truncate)
    Route::resource('participants', ParticipantCrudController::class);
    Route::post('participants-import', [ParticipantCrudController::class, 'importCsv'])->name('participants.import');
    Route::post('participants-truncate', [ParticipantCrudController::class, 'truncate'])->name('participants.truncate');

    // 4. Setting Registrasi (Form Dinamis & Background)
    Route::get('/registration-settings', [RegistrationSettingController::class, 'index'])->name('registration-settings.index');
    Route::post('/registration-settings/update-general', [RegistrationSettingController::class, 'updateGeneral'])->name('registration-settings.update-general');
    Route::post('/registration-settings/fields', [RegistrationSettingController::class, 'storeField'])->name('registration-settings.fields.store');
    Route::delete('/registration-settings/fields/{id}', [RegistrationSettingController::class, 'destroyField'])->name('registration-settings.fields.destroy');

});

// Auth Routes Bawaan Laravel
require __DIR__.'/auth.php';