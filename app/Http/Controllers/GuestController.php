<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\RegistrationField;
use App\Models\ParticipantAnswer;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GuestController extends Controller
{
    // --- FITUR PENDAFTARAN ---
    
    public function showRegistrationForm($slug = null)
    {
        $isOpen = Setting::getValue('registration_open', '0');
        if ($isOpen == '0') {
            return view('guest.closed');
        }

        $fields = RegistrationField::orderBy('order')->get();
        
        // SINKRONISASI ADMIN: Ambil Judul Form
        $formTitle = Setting::getValue('form_title', 'Form Pendaftaran Acara');

        // SINKRONISASI ADMIN: Ambil Background & Auto-Fix foldernya
        $rawBackground = Setting::getValue('event_background');
        $eventBackground = null;
        
        if ($rawBackground) {
            // Bersihkan path
            $cleanPath = str_replace(['public/', 'storage/', 'public\\', 'storage\\'], '', $rawBackground);
            $cleanPath = ltrim($cleanPath, '/\\');

            // Auto-Fix penyesuaian folder uploads/backgrounds
            if (!str_contains($cleanPath, 'uploads/') && str_contains($cleanPath, 'backgrounds')) {
                $cleanPath = 'uploads/' . $cleanPath;
            }

            $eventBackground = $cleanPath;
        }

        $settings = [
            'event_title' => $formTitle,
            'background'  => $eventBackground
        ];

        return view('guest.register', compact('fields', 'settings', 'formTitle', 'eventBackground', 'isOpen', 'slug'));
    }

    public function submitRegistration(Request $request, $slug = null)
    {
        // 1. Validasi Data Inti
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20', 
        ]);

        // 2. PENYEMPURNAAN: Validasi Data Dinamis (Mengecek is_required dari database)
        $fields = RegistrationField::all();
        $dynamicValidationRules = [];
        $dynamicValidationMessages = [];

        foreach ($fields as $field) {
            $inputName = 'dynamic_' . $field->id;
            
            // Jika kolom tersebut diatur WAJIB oleh admin
            if ($field->is_required) {
                $dynamicValidationRules[$inputName] = 'required';
                $dynamicValidationMessages[$inputName . '.required'] = 'Kolom "' . $field->label . '" wajib diisi.';
            }
        }

        // Jalankan validasi dinamis jika ada
        if (!empty($dynamicValidationRules)) {
            $request->validate($dynamicValidationRules, $dynamicValidationMessages);
        }

        // 3. Cek apakah nomor WA sudah pernah daftar di BATCH/SLUG yang SAMA
        $cekDaftar = Participant::where('phone', $request->phone)
                        ->where('slug', $slug)
                        ->first();
                        
        if ($cekDaftar) {
            return back()->withInput()->with('error', 'Nomor WhatsApp ini sudah terdaftar di acara ini.');
        }

        // 4. Simpan Data Inti Peserta + Slug Acara
        $participant = Participant::create([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'slug'  => $slug, // <-- INI YANG BIKIN BATCH TERPISAH
        ]);

        // 5. Simpan Data Tambahan (Field Dinamis)
        foreach ($fields as $field) {
            $inputName = 'dynamic_' . $field->id;
            
            if ($request->has($inputName) && !empty($request->input($inputName))) {
                ParticipantAnswer::create([
                    'participant_id' => $participant->id,
                    'registration_field_id' => $field->id,
                    'answer_value' => $request->input($inputName),
                ]);
            }
        }

        return back()->with('success', 'Pendaftaran Anda berhasil! Terima kasih.');
    }

    // --- FITUR DOWNLOAD BACKGROUND ---

    public function downloadBackground()
    {
        $rawBackground = Setting::getValue('event_background') ?? Setting::getValue('registration_background');

        if (!$rawBackground) {
            return back()->with('error', 'File background belum diunggah oleh panitia.');
        }

        $cleanPath = str_replace(['public/', 'storage/', 'public\\', 'storage\\'], '', $rawBackground);
        $cleanPath = ltrim($cleanPath, '/\\');

        if (!str_contains($cleanPath, 'uploads/') && str_contains($cleanPath, 'backgrounds')) {
            $cleanPath = 'uploads/' . $cleanPath;
        }

        $fullPath = public_path($cleanPath);

        if (!File::exists($fullPath)) {
            return back()->with('error', 'File background tidak ditemukan di server.');
        }

        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $namaFileBaru = 'Background_Virtual_Acara.' . $extension;

        return response()->download($fullPath, $namaFileBaru);
    }

    // --- FITUR SERTIFIKAT ---

    public function searchCertificate($slug = null)
    {
        $eventName = Setting::getValue('event_name', 'Acara Kami');
        $isOpen = Setting::getValue('certificate_open', '0');
        
        $marqueeText = Setting::getValue('marquee_text', 'Selamat datang di Portal Unduh Sertifikat. Silakan masukkan Nomor WhatsApp Anda.');
        
        $logoPath = Setting::getValue('event_logo');
        $eventLogo = $logoPath ? asset('storage/' . str_replace('public/', '', $logoPath)) : null;
        
        return view('guest.certificate_search', compact('eventName', 'isOpen', 'slug', 'marqueeText', 'eventLogo'));
    }

    // Check Participant via AJAX (PENYEMPURNAAN MULTI-BATCH)
    public function checkParticipant(Request $request)
    {
        $query = Participant::where('phone', $request->phone);
        
        // Filter berdasarkan slug acara jika ada (agar tidak tertukar dengan acara sebelumnya)
        if ($request->has('slug') && !empty($request->slug)) {
            $query->where('slug', $request->slug);
        }

        $participant = $query->first();
        
        if ($participant) {
            // Sertakan parameter slug di link download
            $downloadLink = route('guest.certificate.download', [
                'phone' => $participant->phone, 
                'slug' => $participant->slug
            ]);
            
            return response()->json([
                'status' => 'success',
                'name' => $participant->name,
                'link' => $downloadLink
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak ditemukan. Pastikan No. WhatsApp sesuai dengan acara ini.'
        ]);
    }

    // Download Sertifikat (PENYEMPURNAAN MULTI-BATCH)
    public function downloadCertificate(Request $request)
    {
        if (!$request->phone) {
            return redirect('/')->with('error', 'Link tidak valid atau Nomor WhatsApp tidak disertakan.');
        }

        $isOpen = Setting::getValue('certificate_open', '0');
        if ($isOpen == '0') {
            return back()->with('error', 'Maaf, portal unduh sertifikat saat ini sedang ditutup.');
        }

        // Cari data peserta berdasarkan Nomor WA dan SLUG acara
        $query = Participant::where('phone', $request->phone);
        if ($request->has('slug') && !empty($request->slug)) {
            $query->where('slug', $request->slug);
        }

        $participant = $query->first();
        
        if (!$participant) {
            return back()->with('error', 'Data tidak ditemukan. Pastikan No. WhatsApp sesuai dengan acara ini.');
        }

        $templatePath = Setting::getValue('certificate_template');
        if (!$templatePath) {
            return back()->with('error', 'Sertifikat belum siap. Panitia belum mengunggah template.');
        }

        $relativePath = str_replace('public/', '', $templatePath);

        if (!Storage::disk('public')->exists($relativePath)) {
            return back()->with('error', 'File template hilang dari server.');
        }

        $fullPath = Storage::disk('public')->path($relativePath);

        $serialFormat = Setting::getValue('certificate_serial_format', 'CERT/');
        $serialNumber = $serialFormat . str_pad($participant->id, 3, '0', STR_PAD_LEFT);
        
        $nameX = Setting::getValue('name_x', 500);
        $nameY = Setting::getValue('name_y', 400);
        $nameAlign = Setting::getValue('name_align', 'center');
        
        $serialX = Setting::getValue('serial_x', 500);
        $serialY = Setting::getValue('serial_y', 700);
        $serialAlign = Setting::getValue('serial_align', 'center');

        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $data = file_get_contents($fullPath);
        $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdf = Pdf::loadView('guest.certificate_pdf', [
            'participant' => $participant,
            'serialNumber' => $serialNumber,
            'base64Image' => $base64Image,
            'nameX' => $nameX,
            'nameY' => $nameY,
            'nameAlign' => $nameAlign,
            'serialX' => $serialX,
            'serialY' => $serialY,
            'serialAlign' => $serialAlign,
        ])->setPaper('A4', 'landscape');

        return $pdf->download('Sertifikat - ' . $participant->name . '.pdf');
    }
}