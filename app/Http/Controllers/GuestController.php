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

    public function download(string $nim)
    {
        $participant = Participant::where('nim', $nim)->firstOrFail();
        $template = Setting::where('key', 'template_path')->first();
        
        $base64Image = null;
        if ($template && $template->value) {
            // CARA BARU: Membaca file menggunakan facade Storage bawaan Laravel
            if (Storage::exists($template->value)) {
                $ext = pathinfo($template->value, PATHINFO_EXTENSION);
                $data = Storage::get($template->value);
                $base64Image = 'data:image/' . $ext . ';base64,' . base64_encode($data);
            }
        }

        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                  ->loadView('certificate', compact('participant', 'base64Image'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat - ' . $participant->name . '.pdf');
    }
}