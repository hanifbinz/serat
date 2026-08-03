<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class ParticipantCrudController extends Controller
{
    public function index()
    {
        // Load relasi 'answers' untuk bisa melihat data dinamis di tabel admin nanti
        $participants = Participant::with('answers.registrationField')->latest()->paginate(20);
        return view('admin.participants.index', compact('participants'));
    }

    public function create()
    {
        return view('admin.participants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:participants,phone',
        ]);

        Participant::create($request->only(['name', 'phone']));

        return redirect()->route('admin.participants.index')->with('success', 'Data peserta berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $participant = Participant::findOrFail($id);
        return view('admin.participants.edit', compact('participant'));
    }

    public function update(Request $request, int $id)
    {
        $participant = Participant::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:participants,phone,' . $participant->id,
        ]);

        $participant->update($request->only(['name', 'phone']));

        return redirect()->route('admin.participants.index')->with('success', 'Data peserta berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        Participant::findOrFail($id)->delete();
        return back()->with('success', 'Peserta berhasil dihapus!');
    }

    // --- FITUR MASSAL YANG DIPINDAH DARI DASHBOARD ---

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), "r");
        
        $header = true;
        while ($csvLine = fgetcsv($handle, 1000, ",")) {
            if ($header) {
                $header = false; // Lewati baris pertama (judul kolom)
                continue;
            }
            
            // Asumsi format CSV: Kolom 0 = Nama, Kolom 1 = Phone
            if (isset($csvLine[0]) && isset($csvLine[1])) {
                Participant::updateOrCreate(
                    ['phone' => $csvLine[1]], // Cari berdasarkan WA agar tidak dobel
                    ['name' => $csvLine[0]]
                );
            }
        }
        fclose($handle);

        return back()->with('success', 'Data peserta dari CSV berhasil diimpor!');
    }

    public function truncate()
    {
        // Menggunakan query builder agar aman untuk sqlite, lalu mereset ID ke 1
        Participant::query()->delete();
        DB::statement('DELETE FROM sqlite_sequence WHERE name="participants"');

        return back()->with('success', 'Semua data peserta berhasil dikosongkan!');
    }
}