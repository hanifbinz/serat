<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    // 1. Menampilkan halaman depan sesuai status portal (Buka/Tutup)
    public function index()
    {
        $sessionStatus = Setting::where('key', 'session_status')->first();
        $isOpen = $sessionStatus && $sessionStatus->value === 'open';
        
        return view('welcome', compact('isOpen'));
    }

    // 2. Fungsi untuk dipanggil JavaScript (Mengecek nama secara live)
    public function checkNim(string $nim)
    {
        $participant = Participant::where('nim', $nim)->first();
        if ($participant) {
            return response()->json(['status' => 'success', 'name' => $participant->name]);
        }
        return response()->json(['status' => 'error', 'message' => 'NIM tidak ditemukan.']);
    }

    // 3. Proses validasi saat tombol unduh ditekan
    public function processClaim(Request $request)
    {
        $sessionStatus = Setting::where('key', 'session_status')->first();
        if (!$sessionStatus || $sessionStatus->value !== 'open') {
            return redirect('/')->with('error', 'Maaf, Portal Unduhan saat ini sedang ditutup oleh panitia.');
        }

        $request->validate(['nim' => 'required|string']);
        $participant = Participant::where('nim', $request->nim)->first();

        if (!$participant) {
            return back()->with('error', 'Nomor WhatsApp Anda tidak terdaftar di database.');
        }

        // Buat token unduh sekali pakai
        $downloadToken = md5($participant->nim . env('APP_KEY', 'rahasia') . time());
        session(['download_token_' . $participant->id => $downloadToken]);

        // Arahkan ke rute eksekusi PDF
        return redirect()->route('download.token', ['token' => $downloadToken, 'id' => $participant->id]);
    }

    // 4. Eksekusi pembuatan dan pengunduhan PDF
    public function downloadByToken(string $token, int $id)
    {
        $participant = Participant::findOrFail($id);

        // Cek apakah token valid
        if (session('download_token_' . $participant->id) !== $token) {
            return redirect('/')->with('error', 'Akses unduh tidak valid atau sudah kedaluwarsa.');
        }

        // Hapus token agar tidak bisa digunakan ulang (Sekali pakai)
        session()->forget('download_token_' . $participant->id);

        // Ambil template dan ubah ke Base64
        $template = Setting::where('key', 'template_path')->first();
        $base64Image = null;
        
        if ($template && $template->value) {
            if (Storage::exists($template->value)) {
                $ext = pathinfo($template->value, PATHINFO_EXTENSION);
                $data = Storage::get($template->value);
                $base64Image = 'data:image/' . $ext . ';base64,' . base64_encode($data);
            }
        }

        // Ambil format prefix nomor sertifikat
        $prefixSetting = Setting::where('key', 'certificate_prefix')->first();
        $prefix = $prefixSetting ? $prefixSetting->value : 'SCAR/2026/VI/';

        // Render PDF
        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                  ->loadView('certificate', compact('participant', 'base64Image', 'prefix'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat - ' . $participant->name . '.pdf');
    }
}