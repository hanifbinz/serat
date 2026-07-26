<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $sessionStatus = Setting::where('key', 'session_status')->first();
        $isOpen = $sessionStatus && $sessionStatus->value === 'open';
        
        // --- BARU: Tarik data judul acara dan lempar ke View ---
        $titleSetting = Setting::where('key', 'seminar_title')->first();
        $seminarTitle = $titleSetting ? $titleSetting->value : 'SCAR 2026';
        
        return view('welcome', compact('isOpen', 'seminarTitle'));
    }

    public function checkNim(string $nim)
    {
        $participant = Participant::where('nim', $nim)->first();
        if ($participant) {
            return response()->json(['status' => 'success', 'name' => $participant->name]);
        }
        return response()->json(['status' => 'error', 'message' => 'NIM tidak ditemukan.']);
    }

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

        $downloadToken = md5($participant->nim . env('APP_KEY', 'rahasia') . time());
        session(['download_token_' . $participant->id => $downloadToken]);

        return redirect()->route('download.token', ['token' => $downloadToken, 'id' => $participant->id]);
    }

    public function downloadByToken(string $token, int $id)
    {
        $participant = Participant::findOrFail($id);

        if (session('download_token_' . $participant->id) !== $token) {
            return redirect('/')->with('error', 'Akses unduh tidak valid atau sudah kedaluwarsa.');
        }

        session()->forget('download_token_' . $participant->id);

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