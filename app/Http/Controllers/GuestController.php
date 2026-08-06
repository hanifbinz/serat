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
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $participant = Participant::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        $fields = RegistrationField::all();
        foreach ($fields as $field) {
            $inputName = 'field_' . $field->id;
            if ($request->has($inputName)) {
                ParticipantAnswer::create([
                    'participant_id' => $participant->id,
                    'registration_field_id' => $field->id,
                    'answer_value' => $request->input($inputName),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pendaftaran berhasil!');
    }

    // --- FITUR DOWNLOAD BACKGROUND (DITAMBAHKAN) ---

   public function downloadBackground()
    {
        $rawBackground = Setting::getValue('event_background') ?? Setting::getValue('registration_background');

        if (!$rawBackground) {
            return back()->with('error', 'File background belum diunggah oleh panitia.');
        }

        // Bersihkan path
        $cleanPath = str_replace(['public/', 'storage/', 'public\\', 'storage\\'], '', $rawBackground);
        $cleanPath = ltrim($cleanPath, '/\\');

        if (!str_contains($cleanPath, 'uploads/') && str_contains($cleanPath, 'backgrounds')) {
            $cleanPath = 'uploads/' . $cleanPath;
        }

        $fullPath = public_path($cleanPath);

        if (!File::exists($fullPath)) {
            return back()->with('error', 'File background tidak ditemukan di server.');
        }

        // JURUS BARU: Paksa ekstensi dan nama file saat didownload 
        // agar tidak mungkin tertukar dengan PDF sertifikat!
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

    // Check Participant via AJAX
    public function checkParticipant(Request $request)
    {
        $participant = Participant::where('phone', $request->phone)->first();
        
        if ($participant) {
            $downloadLink = route('guest.certificate.download', ['phone' => $participant->phone]);
            
            return response()->json([
                'status' => 'success',
                'name' => $participant->name,
                'link' => $downloadLink
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak ditemukan. Pastikan No. WhatsApp sesuai dengan yang didaftarkan.'
        ]);
    }

    public function downloadCertificate(Request $request)
    {
        if (!$request->phone) {
            return redirect('/')->with('error', 'Link tidak valid atau Nomor WhatsApp tidak disertakan.');
        }

        // 1. Cek apakah portal buka?
        $isOpen = Setting::getValue('certificate_open', '0');
        if ($isOpen == '0') {
            return back()->with('error', 'Maaf, portal unduh sertifikat saat ini sedang ditutup.');
        }

        // 2. Cari data peserta berdasarkan Nomor WA
        $participant = Participant::where('phone', $request->phone)->first();
        if (!$participant) {
            return back()->with('error', 'Data tidak ditemukan. Pastikan No. WhatsApp sesuai dengan yang didaftarkan.');
        }

        // 3. Cek template sertifikat
        $templatePath = Setting::getValue('certificate_template');
        if (!$templatePath) {
            return back()->with('error', 'Sertifikat belum siap. Panitia belum mengunggah template.');
        }

        $relativePath = str_replace('public/', '', $templatePath);

        if (!Storage::disk('public')->exists($relativePath)) {
            return back()->with('error', 'File template hilang dari server.');
        }

        $fullPath = Storage::disk('public')->path($relativePath);

        // 4. Generate Nomor Serial
        $serialFormat = Setting::getValue('certificate_serial_format', 'CERT/');
        $serialNumber = $serialFormat . str_pad($participant->id, 3, '0', STR_PAD_LEFT);
        
        // 5. Ambil Koordinat dan Penyelarasan
        $nameX = Setting::getValue('name_x', 500);
        $nameY = Setting::getValue('name_y', 400);
        $nameAlign = Setting::getValue('name_align', 'center');
        
        $serialX = Setting::getValue('serial_x', 500);
        $serialY = Setting::getValue('serial_y', 700);
        $serialAlign = Setting::getValue('serial_align', 'center');

        // 6. Convert Gambar ke Base64 untuk DomPDF
        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $data = file_get_contents($fullPath);
        $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // 7. Render PDF
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