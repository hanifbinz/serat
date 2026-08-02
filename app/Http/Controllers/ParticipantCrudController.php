<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Setting;
use Illuminate\Http\Request;

class ParticipantCrudController extends Controller
{
    public function index(Request $request)
    {
        $query = Participant::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $participants = $query->latest()->paginate(15)->withQueryString();

        return view('admin.participants', compact('participants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|unique:participants,nim',
            'name' => 'required|string|max:255',
        ], [
            'nim.unique' => 'Nomor WhatsApp / NIM ini sudah terdaftar!',
            'nim.required' => 'Nomor WhatsApp wajib diisi.',
            'name.required' => 'Nama lengkap wajib diisi.',
        ]);

        Participant::create($request->only('nim', 'name'));

        return back()->with('success', 'Data peserta berhasil ditambahkan.');
    }

    // Perbaikan: Tambahkan tipe data "string" pada $id
    public function update(Request $request, string $id) 
    {
        $participant = Participant::findOrFail($id);

        $request->validate([
            'nim' => 'required|string|unique:participants,nim,' . $id,
            'name' => 'required|string|max:255',
        ], [
            'nim.unique' => 'Nomor WhatsApp / NIM ini sudah digunakan peserta lain!',
            'nim.required' => 'Nomor WhatsApp wajib diisi.',
            'name.required' => 'Nama lengkap wajib diisi.',
        ]);

        $participant->update($request->only('nim', 'name'));

        return back()->with('success', 'Data peserta berhasil diperbarui.');
    }

    // Perbaikan: Tambahkan tipe data "string" pada $id
    public function destroy(string $id) 
    {
        $participant = Participant::findOrFail($id);
        $participant->delete();

        return back()->with('success', 'Data peserta berhasil dihapus.');
    }

    public function registrationSetting()
    {
        $regStatus = Setting::where('key', 'registration_status')->first();
        $isRegOpen = $regStatus && $regStatus->value === 'open';

        return view('admin.registration_setting', compact('isRegOpen'));
    }

    public function toggleRegistration()
    {
        $regStatus = Setting::where('key', 'registration_status')->first();
        $newValue = ($regStatus && $regStatus->value === 'open') ? 'closed' : 'open';

        Setting::updateOrCreate(
            ['key' => 'registration_status'],
            ['value' => $newValue]
        );

        return back()->with('success', 'Status Form Registrasi Publik berhasil diubah!');
    }
}