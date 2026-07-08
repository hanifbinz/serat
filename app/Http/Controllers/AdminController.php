<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- Tambahan untuk menghapus file gambar

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

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
        return view('admin.dashboard', compact('participantsCount', 'template'));
    }

    public function uploadData(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $file = $request->file('file')->getRealPath();
        
        $data = array_map('str_getcsv', file($file));
        
        // Asumsi format CSV: kolom 1 = NIM, kolom 2 = Nama
        foreach ($data as $row) {
            if(isset($row[0]) && isset($row[1])){
                Participant::updateOrCreate(
                    ['nim' => $row[0]],
                    ['name' => $row[1]]
                );
            }
        }
        return back()->with('success', 'Data peserta berhasil diunggah.');
    }

    public function uploadTemplate(Request $request)
    {
        $request->validate(['template' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $path = $request->file('template')->store('public/templates');
        
        Setting::updateOrCreate(
            ['key' => 'template_path'],
            ['value' => $path]
        );
        return back()->with('success', 'Template sertifikat berhasil diunggah.');
    }

    // --- FITUR BARU: HAPUS DATA & TEMPLATE ---

    public function clearData()
    {
        // Menghapus semua data peserta dan reset nomor urut (ID) ke 1
        Participant::truncate();
        return back()->with('success', 'Semua data peserta berhasil dikosongkan!');
    }

    public function clearTemplate()
    {
        // Mencari data template di database
        $setting = Setting::where('key', 'template_path')->first();
        
        if ($setting) {
            // Menghapus file gambar fisiknya dari storage
            if (Storage::exists($setting->value)) {
                Storage::delete($setting->value);
            }
            // Menghapus record dari database
            $setting->delete();
        }

        return back()->with('success', 'Template sertifikat berhasil dihapus!');
    }
}