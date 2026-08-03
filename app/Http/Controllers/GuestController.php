<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Setting;
use App\Models\RegistrationField;
use App\Models\ParticipantAnswer;
use Illuminate\Support\Facades\Storage;

class GuestController extends Controller
{
    public function showRegistrationForm()
    {
        // Cek apakah pendaftaran dibuka
        $isOpen = Setting::getValue('registration_open', '1');
        if ($isOpen !== '1') {
            return view('guest.closed', ['message' => 'Mohon maaf, pendaftaran saat ini sedang ditutup.']);
        }

        $formTitle = Setting::getValue('form_title', 'Form Pendaftaran Acara');
        $eventBackground = Setting::getValue('event_background');
        $fields = RegistrationField::all();

        return view('guest.register', compact('formTitle', 'eventBackground', 'fields'));
    }

    public function submitRegistration(Request $request)
    {
        // 1. Validasi Dasar (Nama & No WA)
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:participants,phone',
        ];

        // 2. Validasi Dinamis (Looping dari tabel field yang dibuat admin)
        $fields = RegistrationField::all();
        foreach ($fields as $field) {
            $rule = $field->is_required ? 'required' : 'nullable';
            $rule .= $field->type === 'number' ? '|numeric' : '|string';
            
            $rules['dynamic_' . $field->id] = $rule;
        }

        $request->validate($rules);

        // 3. Simpan ke database peserta (Tabel Utama)
        $participant = Participant::create([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        // 4. Simpan jawaban dinamis ke tabel jawaban (Relasi)
        foreach ($fields as $field) {
            if ($request->has('dynamic_' . $field->id)) {
                ParticipantAnswer::create([
                    'participant_id' => $participant->id,
                    'registration_field_id' => $field->id,
                    'answer_value' => $request->input('dynamic_' . $field->id),
                ]);
            }
        }

        return back()->with('success', 'Pendaftaran berhasil! Terima kasih sudah mendaftar.');
    }

    public function downloadBackground()
    {
        $backgroundPath = Setting::getValue('event_background');
        
        if ($backgroundPath && Storage::exists($backgroundPath)) {
            return Storage::download($backgroundPath);
        }

        return back()->with('error', 'File background belum tersedia.');
    }

    public function searchCertificate()
    {
        $isOpen = Setting::getValue('certificate_open', '0');
        $eventName = Setting::getValue('event_name', 'Nama Acara Default');
        
        return view('guest.certificate', compact('isOpen', 'eventName'));
    }

    public function downloadCertificate(Request $request)
    {
        $request->validate(['phone' => 'required|string']);
        
        $participant = Participant::where('phone', $request->phone)->first();
        
        if (!$participant) {
            return back()->with('error', 'Nomor WhatsApp tidak ditemukan.');
        }

        // Catatan: Ini adalah placeholder. Nanti logika cetak/intervensi gambar sertifikat 
        // yang lama bisa dimasukkan ke dalam blok ini.
        return back()->with('success', 'Sertifikat siap diunduh.');
    }
}