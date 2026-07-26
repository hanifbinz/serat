<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function showLogin() { return view('admin.login'); }
    
    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('admin/dashboard');
        }
        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function dashboard()
    {
        $participantsCount = Participant::count();
        $template = Setting::where('key', 'template_path')->first();
        
        $prefixSetting = Setting::where('key', 'certificate_prefix')->first();
        $prefixValue = $prefixSetting ? $prefixSetting->value : 'SCAR/2026/VI/';
        
        $sessionStatus = Setting::where('key', 'session_status')->first();
        $isOpen = $sessionStatus && $sessionStatus->value === 'open';

        // --- BARU: Tarik data judul seminar ---
        $titleSetting = Setting::where('key', 'seminar_title')->first();
        $seminarTitle = $titleSetting ? $titleSetting->value : 'SCAR 2026';
        
        return view('admin.dashboard', compact('participantsCount', 'template', 'prefixValue', 'isOpen', 'seminarTitle'));
    }

    public function uploadData(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $file = $request->file('file')->getRealPath();
        $data = array_map('str_getcsv', file($file));
        foreach ($data as $row) {
            if(isset($row[0]) && isset($row[1])) { Participant::updateOrCreate(['nim' => $row[0]], ['name' => $row[1]]); }
        }
        return back()->with('success', 'Data peserta berhasil diunggah.');
    }

    public function uploadTemplate(Request $request)
    {
        $request->validate(['template' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $path = $request->file('template')->store('public/templates');
        Setting::updateOrCreate(['key' => 'template_path'], ['value' => $path]);
        return back()->with('success', 'Template sertifikat berhasil diunggah.');
    }

    public function clearData() { Participant::truncate(); return back()->with('success', 'Data berhasil dikosongkan!'); }

    public function clearTemplate()
    {
        $setting = Setting::where('key', 'template_path')->first();
        if ($setting) {
            if (Storage::exists($setting->value)) Storage::delete($setting->value);
            $setting->delete();
        }
        return back()->with('success', 'Template dihapus!');
    }

    public function savePrefix(Request $request)
    {
        $request->validate(['prefix' => 'required|string']);
        Setting::updateOrCreate(['key' => 'certificate_prefix'], ['value' => $request->prefix]);
        return back()->with('success', 'Format Nomor Sertifikat berhasil diperbarui!');
    }

    public function openSession()
    {
        Setting::updateOrCreate(['key' => 'session_status'], ['value' => 'open']);
        return back()->with('success', 'Portal Unduhan DIBUKA!');
    }

    public function closeSession()
    {
        Setting::updateOrCreate(['key' => 'session_status'], ['value' => 'closed']);
        return back()->with('success', 'Portal Unduhan DITUTUP!');
    }

    // --- BARU: Fungsi Simpan Judul Seminar ---
    public function saveSeminarTitle(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        Setting::updateOrCreate(['key' => 'seminar_title'], ['value' => $request->title]);
        return back()->with('success', 'Judul Seminar berhasil diperbarui!');
    }
}