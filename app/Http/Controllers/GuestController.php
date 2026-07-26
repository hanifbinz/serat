<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    // 1. Jika buka web utama tanpa link, otomatis ke halaman "Terkunci"
    public function index()
    {
        return view('welcome', ['code' => null]);
    }

    // 2. Jika link valid, tampilkan form unduh
    public function showClaimForm(string $code)
    {
        $activeLink = Setting::where('key', 'active_claim_link')->first();
        if (!$activeLink || $activeLink->value !== $code) {
            return redirect('/')->with('error', 'Maaf, link unduh tidak valid atau sesi sudah ditutup oleh panitia.');
        }
        return view('welcome', compact('code'));
    }

    // 3. Proses form jika nomor WA dimasukkan
    public function processClaim(Request $request, string $code)
    {
        $activeLink = Setting::where('key', 'active_claim_link')->first();
        if (!$activeLink || $activeLink->value !== $code) {
            return redirect('/')->with('error', 'Sesi unduh sudah ditutup.');
        }

        $request->validate(['nim' => 'required|string']);
        $participant = Participant::where('nim', $request->nim)->first();

        if (!$participant) {
            return back()->with('error', 'Nomor WhatsApp Anda tidak terdaftar di database.');
        }

        // Buat token sekali pakai untuk mengunduh
        $downloadToken = md5($participant->nim . $code . env('APP_KEY', 'rahasia'));
        session(['download_token_' . $participant->id => $downloadToken]);

        return redirect()->route('download.token', ['token' => $downloadToken, 'id' => $participant->id]);
    }

    // 4. Unduh PDF final jika token cocok
    public function downloadByToken(string $token, int $id)
    {
        $participant = Participant::findOrFail($id);

        if (session('download_token_' . $participant->id) !== $token) {
            return redirect('/')->with('error', 'Akses unduh tidak valid atau sudah kedaluwarsa.');
        }

        $template = Setting::where('key', 'template_path')->first();
        
        $base64Image = null;
        if ($template && $template->value) {
            if (Storage::exists($template->value)) {
                $ext = pathinfo($template->value, PATHINFO_EXTENSION);
                $data = Storage::get($template->value);
                $base64Image = 'data:image/' . $ext . ';base64,' . base64_encode($data);
            }
        }

        $prefixSetting = Setting::where('key', 'certificate_prefix')->first();
        $prefix = $prefixSetting ? $prefixSetting->value : 'SCAR/2026/VI/';

        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                  ->loadView('certificate', compact('participant', 'base64Image', 'prefix'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat - ' . $participant->name . '.pdf');
    }
}