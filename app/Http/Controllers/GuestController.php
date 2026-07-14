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
        return view('welcome');
    }

    public function checkNim(string $nim)
    {
        $participant = Participant::where('nim', $nim)->first();
        if ($participant) {
            return response()->json(['status' => 'success', 'name' => $participant->name]);
        }
        return response()->json(['status' => 'error', 'message' => 'NIM tidak ditemukan.']);
    }

    public function showCheckin(string $code)
    {
        $activeLink = Setting::where('key', 'active_checkin_link')->first();
        
        if (!$activeLink || $activeLink->value !== $code) {
            return abort(404, 'Maaf, link absen ini sudah kadaluarsa atau tidak valid.');
        }

        return view('guest.checkin', compact('code'));
    }

    public function processCheckin(Request $request, string $code)
    {
        $activeLink = Setting::where('key', 'active_checkin_link')->first();
        if (!$activeLink || $activeLink->value !== $code) {
            return abort(404, 'Sesi absensi sudah ditutup.');
        }

        $request->validate(['nim' => 'required|string']);

        $participant = Participant::where('nim', $request->nim)->first();

        if (!$participant) {
            return back()->with('error', 'Nomor WhatsApp Anda tidak terdaftar. Hubungi panitia.');
        }

        if ($participant->is_checked_in) {
            return back()->with('success', 'Anda sudah melakukan Check-In sebelumnya. Silakan unduh sertifikat Anda.');
        }

        $participant->update(['is_checked_in' => true]);

        return back()->with('success', 'Selamat, Check-In berhasil! Sertifikat Anda sudah bisa diunduh sekarang.');
    }

    public function download(string $nim)
    {
        $participant = Participant::where('nim', $nim)->firstOrFail();

        if (!$participant->is_checked_in) {
            return redirect('/')->with('error', 'Maaf, Anda belum melakukan Check-In kehadiran. Sertifikat terkunci.');
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

        // --- BARU: Ambil Prefix Sertifikat ---
        $prefixSetting = Setting::where('key', 'certificate_prefix')->first();
        $prefix = $prefixSetting ? $prefixSetting->value : 'SCAR/2026/VI/';

        // Lempar variabel $prefix ke view
        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                  ->loadView('certificate', compact('participant', 'base64Image', 'prefix'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat - ' . $participant->name . '.pdf');
    }
}