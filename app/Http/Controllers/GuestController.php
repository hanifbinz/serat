<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

    // Fungsi showCheckin dan processCheckin sudah dihapus sepenuhnya

    public function download(string $nim)
    {
        $participant = Participant::where('nim', $nim)->firstOrFail();

        // --- PENGUNCI CHECK-IN SUDAH DIHAPUS DARI SINI ---

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