<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\RegistrationField;

class RegistrationSettingController extends Controller
{
    public function index()
    {
        $fields = RegistrationField::all();
        $formTitle = Setting::getValue('form_title', 'Form Pendaftaran Acara');
        $eventBackground = Setting::getValue('event_background');
        $isOpen = Setting::getValue('registration_open', '1');

        return view('admin.registration-setting.index', compact('fields', 'formTitle', 'eventBackground', 'isOpen'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'form_title' => 'required|string|max:255',
            'event_background' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        Setting::updateOrCreate(['key' => 'form_title'], ['value' => $request->form_title]);
        Setting::updateOrCreate(['key' => 'registration_open'], ['value' => $request->registration_open]);

        if ($request->hasFile('event_background')) {
            $path = $request->file('event_background')->store('public/backgrounds');
            Setting::updateOrCreate(['key' => 'event_background'], ['value' => $path]);
        }

        return back()->with('success', 'Pengaturan form dan background berhasil disimpan!');
    }

    public function storeField(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,number',
        ]);

        RegistrationField::create([
            'label' => $request->label,
            'type' => $request->type,
            'is_required' => true,
        ]);

        return back()->with('success', 'Kolom isian baru berhasil ditambahkan!');
    }

    public function destroyField(int $id)
    {
        RegistrationField::findOrFail($id)->delete();
        return back()->with('success', 'Kolom isian berhasil dihapus!');
    }
}