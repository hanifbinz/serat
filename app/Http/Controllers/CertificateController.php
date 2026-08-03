<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class CertificateController extends Controller
{
    public function index()
    {
        $template = Setting::getValue('certificate_template');
        $isOpen = Setting::getValue('certificate_open', '0');
        $serialFormat = Setting::getValue('certificate_serial_format', 'CERT-2026-[ID]');
        $eventName = Setting::getValue('event_name', 'Nama Acara Default');

        return view('admin.certificate.index', compact('template', 'isOpen', 'serialFormat', 'eventName'));
    }

    public function uploadTemplate(Request $request)
    {
        $request->validate([
            'template' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('template')->store('public/certificates');
        Setting::updateOrCreate(['key' => 'certificate_template'], ['value' => $path]);

        return back()->with('success', 'Template sertifikat berhasil diupload!');
    }

    public function togglePortal(Request $request)
    {
        Setting::updateOrCreate(['key' => 'certificate_open'], ['value' => $request->status]);
        $statusText = $request->status == '1' ? 'dibuka' : 'ditutup';
        
        return back()->with('success', "Portal sertifikat berhasil $statusText!");
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'serial_format' => 'required|string',
            'event_name' => 'required|string',
        ]);

        Setting::updateOrCreate(['key' => 'certificate_serial_format'], ['value' => $request->serial_format]);
        Setting::updateOrCreate(['key' => 'event_name'], ['value' => $request->event_name]);

        return back()->with('success', 'Pengaturan teks sertifikat berhasil disimpan!');
    }
}