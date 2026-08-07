<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; 
use App\Models\Setting;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ParticipantCrudController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\RegistrationSettingController;
use App\Http\Controllers\UserController;

// --- AREA PUBLIK (PESERTA) ---

// 1. Root Domain & Smart Redirect
Route::get('/', function (Request $request) {
    // Ambil format prefix dari Pengaturan Sertifikat
    $activeSlug = Setting::getValue('certificate_serial_format', 'SCARHub/2026/IX/');

    // Misi 1: Cek jika pengunjung menggunakan domain 'han'
    if ($request->getHost() === 'han.majuterus.my.id') {
        // Arahkan paksa ke domain sertifikat, disambung dengan prefix/slug dinamis
        return redirect()->away('https://sertifikat.majuterus.my.id/sertifikat/' . $activeSlug);
    }

    // Misi 2: Jika pengunjung sudah menggunakan domain 'sertifikat', langsung redirect ke rute
    return redirect()->route('guest.certificate.search', ['slug' => $activeSlug]); 
});

// 👇 RUTE SPESIFIK HARUS DI ATAS AGAR TIDAK TERTIMPA WILDCARD SLUG 👇
Route::post('/sertifikat/check', [GuestController::class, 'checkParticipant'])->name('guest.certificate.check');
// Menggunakan 'match' agar aman menerima request dari klik link (GET) maupun sisa cache lama (POST)
Route::match(['get', 'post'], '/sertifikat/download', [GuestController::class, 'downloadCertificate'])->name('guest.certificate.download');
// 👆 ----------------------------------------------------------- 👆

// 2. Link Portal Sertifikat Dinamis (Berdasarkan acara/tahun/bulan)
Route::get('/sertifikat/{slug?}', [GuestController::class, 'searchCertificate'])
    ->where('slug', '.*') // Mengizinkan karakter slash (/)
    ->name('guest.certificate.search');
    
// 3. Link Pendaftaran Dinamis 
Route::get('/register/{slug?}', [GuestController::class, 'showRegistrationForm'])
    ->where('slug', '.*')
    ->name('guest.register');
    
Route::post('/register/{slug?}', [GuestController::class, 'submitRegistration'])
    ->where('slug', '.*')
    ->name('guest.register.submit');
    
Route::get('/download-background', [GuestController::class, 'downloadBackground'])->name('guest.download.background');


// --- AREA ADMIN ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Dashboard 
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // 2. Kelola Sertifikat
    Route::get('/certificate', [CertificateController::class, 'index'])->name('certificate.index');
    Route::post('/certificate/update', [CertificateController::class, 'updateAllSettings'])->name('certificate.update');
    
    // 3. Data Peserta (CRUD + CSV + Truncate)
    Route::resource('participants', ParticipantCrudController::class);
    Route::get('participants/{id}/download-cert', [ParticipantCrudController::class, 'downloadCert'])->name('participants.download-cert');
    Route::post('participants-import', [ParticipantCrudController::class, 'importCsv'])->name('participants.import');
    Route::post('participants-truncate', [ParticipantCrudController::class, 'truncate'])->name('participants.truncate');
    
    // 4. Setting Registrasi (Form Dinamis & Background)
    Route::get('/registration-settings', [RegistrationSettingController::class, 'index'])->name('registration-settings.index');
    Route::post('/registration-settings/update-general', [RegistrationSettingController::class, 'updateGeneral'])->name('registration-settings.update-general');
    Route::post('/registration-settings/fields', [RegistrationSettingController::class, 'storeField'])->name('registration-settings.fields.store');
    Route::put('/registration-settings/fields/{id}', [RegistrationSettingController::class, 'updateField'])->name('registration-settings.fields.update');
    Route::delete('/registration-settings/fields/{id}', [RegistrationSettingController::class, 'destroyField'])->name('registration-settings.fields.destroy');

    // 5. Kelola Users
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

});


// --- AUTHENTICATION ROUTES ---

Route::get('/login', function () {
    return view('admin.login'); 
})->name('login')->middleware('guest');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('admin/dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/'); 
})->name('logout');