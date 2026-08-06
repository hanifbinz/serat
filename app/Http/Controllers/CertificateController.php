<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class CertificateController extends Controller
{
    public function index()
    {
        $keys = [
            'certificate_template', 'certificate_open', 
            'certificate_serial_format', 'event_name',
            'name_x', 'name_y', 'serial_x', 'serial_y',
            'name_align', 'serial_align', // <-- KUNCI BARU
            'event_logo', 'marquee_text'
        ];
        
        $settings = [];
        foreach ($keys as $key) {
            $default = '';
            if($key == 'name_x') $default = '500';
            if($key == 'name_y') $default = '400';
            if($key == 'name_align') $default = 'center'; // <-- DEFAULT RATA TENGAH
            
            if($key == 'serial_x') $default = '500';
            if($key == 'serial_y') $default = '700';
            if($key == 'serial_align') $default = 'center'; // <-- DEFAULT RATA TENGAH
            
            if($key == 'certificate_open') $default = '0';
            if($key == 'certificate_serial_format') $default = 'SCAR/2026/VIII/';
            if($key == 'marquee_text') $default = 'Terima kasih telah berpartisipasi dalam acara kami.';
            
            $settings[$key] = Setting::getValue($key, $default);
        }

        return view('admin.certificate.index', compact('settings'));
    }

    public function updateAllSettings(Request $request)
    {
        $request->validate([
            'certificate_bg' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'event_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'certificate_open' => 'required|in:0,1',
            'event_name' => 'required|string|max:255',
            'marquee_text' => 'nullable|string',
            'certificate_serial_format' => 'required|string|max:255',
            'name_x' => 'required|numeric',
            'name_y' => 'required|numeric',
            'name_align' => 'required|in:left,center', // <-- VALIDASI ALIGN
            'serial_x' => 'required|numeric',
            'serial_y' => 'required|numeric',
            'serial_align' => 'required|in:left,center', // <-- VALIDASI ALIGN
        ]);

        if ($request->hasFile('certificate_bg')) {
            $path = $request->file('certificate_bg')->store('certificates', 'public');
            Setting::updateOrCreate(['key' => 'certificate_template'], ['value' => $path]);
        }

        if ($request->hasFile('event_logo')) {
            $logoPath = $request->file('event_logo')->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'event_logo'], ['value' => $logoPath]);
        }

        $settingsToUpdate = [
            'certificate_open' => $request->certificate_open,
            'event_name' => $request->event_name,
            'marquee_text' => $request->marquee_text,
            'certificate_serial_format' => $request->certificate_serial_format,
            'name_x' => $request->name_x,
            'name_y' => $request->name_y,
            'name_align' => $request->name_align, // <-- SIMPAN ALIGN NAMA
            'serial_x' => $request->serial_x,
            'serial_y' => $request->serial_y,
            'serial_align' => $request->serial_align, // <-- SIMPAN ALIGN SERIAL
        ];

        foreach ($settingsToUpdate as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        return back()->with('success', 'Pengaturan dan Desain Sertifikat berhasil disimpan!');
    }
}