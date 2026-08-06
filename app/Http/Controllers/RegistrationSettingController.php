<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\RegistrationField;
use Illuminate\Support\Facades\File; // Wajib ada untuk jurus pembuat folder otomatis

class RegistrationSettingController extends Controller
{
    public function index()
    {
        $fields           = RegistrationField::all();
        $formTitle        = Setting::getValue('form_title', 'Form Pendaftaran Acara');
        
        // AMBIL PATH BACKGROUND
        $rawBackground    = Setting::getValue('event_background');
        $eventBackground  = null;
        
        // Bersihkan format lama jika masih nyangkut di database
        if ($rawBackground) {
            $eventBackground = str_replace(['public/', 'storage/', 'public\\', 'storage\\'], '', $rawBackground);
            $eventBackground = ltrim($eventBackground, '/\\'); // Pastikan tidak ada slash di awal
        }
        
        $isOpen           = Setting::getValue('registration_open', '1');
        $registrationSlug = Setting::getValue('registration_slug', 'scarhub/2026/viii');

        return view('admin.registration-setting.index', compact(
            'fields', 
            'formTitle', 
            'eventBackground', 
            'isOpen', 
            'registrationSlug'
        ));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'form_title'        => 'required|string|max:255',
            'registration_open' => 'required|in:0,1',
            'registration_slug' => 'nullable|string|max:255',
            'event_background'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        Setting::updateOrCreate(['key' => 'form_title'], ['value' => $request->form_title]);
        Setting::updateOrCreate(['key' => 'registration_open'], ['value' => $request->registration_open]);
        
        if ($request->has('registration_slug')) {
            Setting::updateOrCreate(['key' => 'registration_slug'], ['value' => trim($request->registration_slug, '/')]);
        }

        // JURUS PAMUNGKAS: Simpan langsung ke folder public/uploads/backgrounds
        if ($request->hasFile('event_background')) {
            $file = $request->file('event_background');
            
            // Bikin nama file SUPER AMAN (kombinasi waktu & random text, tanpa spasi sama sekali)
            $extension = $file->getClientOriginalExtension();
            $filename  = time() . '_' . uniqid() . '.' . $extension;
            
            // Tentukan folder tujuan
            $destinationPath = public_path('uploads/backgrounds');

            // PAKSA BIKIN FOLDER JIKA DI WINDOWS BELUM ADA
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // Pindahkan langsung ke public directory
            $file->move($destinationPath, $filename);
            
            // Simpan path bersih ke database
            Setting::updateOrCreate(
                ['key' => 'event_background'], 
                ['value' => 'uploads/backgrounds/' . $filename]
            );
        }

        return back()->with('success', 'Pengaturan form dan background berhasil disimpan!');
    }

    public function storeField(Request $request)
    {
        $request->validate([
            'label'       => 'required|string|max:255',
            'type'        => 'required|in:text,number,email,date,textarea',
            'is_required' => 'required|boolean',
        ]);

        RegistrationField::create($request->only('label', 'type', 'is_required'));

        return back()->with('success', 'Kolom isian baru berhasil ditambahkan!');
    }

    public function updateField(Request $request, int $id)
    {
        $request->validate([
            'label'       => 'required|string|max:255',
            'type'        => 'required|in:text,number,email,date,textarea',
            'is_required' => 'required|boolean',
        ]);

        $field = RegistrationField::findOrFail($id);
        $field->update($request->only('label', 'type', 'is_required'));

        return back()->with('success', 'Kolom pendaftaran berhasil diperbarui.');
    }

    public function destroyField(int $id)
    {
        RegistrationField::findOrFail($id)->delete();
        return back()->with('success', 'Kolom isian berhasil dihapus!');
    }
}