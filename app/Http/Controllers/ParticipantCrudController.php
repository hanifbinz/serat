<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Setting; 
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Schema; // <-- Tambahan untuk sistem pengecekan kolom

class ParticipantCrudController extends Controller
{
    // 1. TAMPILKAN DATA & FITUR SEARCH + FILTER BATCH (SLUG)
    public function index(Request $request)
    {
        // 1. Sistem Keamanan: Cek apakah kolom 'slug' sudah ada di database
        $hasSlugColumn = Schema::hasColumn('participants', 'slug');
        $batches = collect();

        // 2. Jika kolom slug ada, baru kita ambil data batch-nya
        if ($hasSlugColumn) {
            $batches = Participant::select('slug')
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->groupBy('slug') // Menggunakan groupBy lebih aman untuk database dibanding distinct
                ->pluck('slug');
        }

        // Gunakan eager loading (with) agar query ke answers & field lebih cepat
        $query = Participant::with('answers.field');

        // Tangkap request batch
        $selectedBatch = $request->input('batch');

        // Logika Filter Berdasarkan Batch (Slug) - Hanya jalankan jika kolom ada
        if ($hasSlugColumn && !empty($selectedBatch)) {
            $query->where('slug', $selectedBatch);
        }

        // Logika Pencarian (Search)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        // Tampilkan 20 data per halaman, urutkan dari yang terbaru
        $participants = $query->latest()->paginate(20);

        return view('admin.participants.index', compact('participants', 'batches', 'selectedBatch'));
    }

    // 2. HALAMAN TAMBAH MANUAL
    public function create()
    {
        return view('admin.participants.create');
    }

    // 3. PROSES SIMPAN DATA BARU
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:participants,phone',
            'email' => 'nullable|email|max:255', 
        ]);

        Participant::create($validated);

        return redirect()->route('admin.participants.index')
            ->with('success', 'Data peserta berhasil ditambahkan manual.');
    }

    // 4. HALAMAN EDIT DATA 
    public function edit($id)
    {
        $participant = Participant::findOrFail($id);
        return view('admin.participants.edit', compact('participant'));
    }

    // 5. PROSES UPDATE DATA (Hanya Nama dan No WA)
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            // Pengecualian unique untuk ID yang sedang diedit agar tidak error
            'phone' => 'required|string|max:20|unique:participants,phone,' . $id, 
        ]);

        $participant = Participant::findOrFail($id);
        $participant->update($validated);

        return redirect()->route('admin.participants.index')
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    // 6. HAPUS 1 PESERTA
    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);
        $participant->delete();

        return back()->with('success', 'Peserta berhasil dihapus.');
    }

    // 7. KOSONGKAN SEMUA DATA (Truncate)
    public function truncate()
    {
        Participant::truncate();
        return back()->with('success', 'Semua data peserta telah dikosongkan.');
    }

    // 8. IMPORT CSV (LOGIKA BARU - ANTI ERROR)
    public function importCsv(Request $request)
    {
        // 1. Validasi file yang diupload harus berupa CSV atau TXT
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120', 
        ]);

        $file = $request->file('file');
        $handle = fopen($file->path(), 'r');

        // 2. Baca baris pertama (Header/Judul Kolom)
        $header = fgetcsv($handle);
        
        // Deteksi pemisah (delimiter). Excel Indonesia biasanya pakai titik koma (;) bukan koma (,)
        $delimiter = ',';
        if (count($header) == 1 && strpos($header[0], ';') !== false) {
            $delimiter = ';';
            fclose($handle);
            $handle = fopen($file->path(), 'r');
            $header = fgetcsv($handle, 1000, $delimiter);
        }

        // Standarisasi nama header jadi huruf kecil semua tanpa spasi berlebih
        $header = array_map('strtolower', $header);
        $header = array_map('trim', $header);

        // 3. Cari posisi kolom secara dinamis (fleksibel)
        $nameIdx = array_search('nama', $header) !== false ? array_search('nama', $header) : array_search('name', $header);
        
        $phoneIdx = array_search('wa', $header);
        if ($phoneIdx === false) $phoneIdx = array_search('phone', $header);
        if ($phoneIdx === false) $phoneIdx = array_search('no wa', $header);
        if ($phoneIdx === false) $phoneIdx = array_search('no_wa', $header);

        $emailIdx = array_search('email', $header);

        // Jika kolom Nama atau WA tidak ditemukan di baris pertama CSV, tolak!
        if ($nameIdx === false || $phoneIdx === false) {
            fclose($handle);
            return back()->with('error', 'Gagal! Format CSV salah. Pastikan baris pertama memiliki kolom bernama "Nama" dan "WA" atau "Phone".');
        }

        $count = 0;

        // 4. Looping untuk membaca isi data dari baris kedua sampai habis
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $name = isset($row[$nameIdx]) ? trim($row[$nameIdx]) : '';
            $phone = isset($row[$phoneIdx]) ? trim($row[$phoneIdx]) : '';
            $email = ($emailIdx !== false && isset($row[$emailIdx])) ? trim($row[$emailIdx]) : null;

            if (!empty($name) && !empty($phone)) {
                Participant::updateOrCreate(
                    ['phone' => $phone],
                    [
                        'name' => $name,
                        'email' => $email
                    ]
                );
                $count++;
            }
        }

        fclose($handle);

        return back()->with('success', "Sukses! $count data peserta berhasil diimpor.");
    }

    // 9. FITUR DOWNLOAD SERTIFIKAT OLEH ADMIN 
    public function downloadCert($id)
    {
        $participant = Participant::findOrFail($id);

        // 1. Cek template sertifikat
        $templatePath = Setting::getValue('certificate_template');
        if (!$templatePath) {
            return back()->with('error', 'Sertifikat belum siap. Anda belum mengunggah template di menu Pengaturan Sertifikat.');
        }

        $relativePath = str_replace('public/', '', $templatePath);
        if (!Storage::disk('public')->exists($relativePath)) {
            return back()->with('error', 'File template hilang dari server. Silakan upload ulang template.');
        }

        $fullPath = Storage::disk('public')->path($relativePath);

        // 2. Generate Nomor Serial
        $serialFormat = Setting::getValue('certificate_serial_format', 'CERT/');
        $serialNumber = $serialFormat . str_pad($participant->id, 3, '0', STR_PAD_LEFT);
        
        // 3. Ambil Koordinat dan Penyelarasan (Alignment)
        $nameX = Setting::getValue('name_x', 500);
        $nameY = Setting::getValue('name_y', 400);
        $nameAlign = Setting::getValue('name_align', 'center'); 
        
        $serialX = Setting::getValue('serial_x', 500);
        $serialY = Setting::getValue('serial_y', 700);
        $serialAlign = Setting::getValue('serial_align', 'center'); 

        // 4. Convert Gambar ke Base64 untuk DomPDF
        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $data = file_get_contents($fullPath);
        $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // 5. Render PDF
        $pdf = Pdf::loadView('guest.certificate_pdf', [
            'participant' => $participant,
            'serialNumber' => $serialNumber,
            'base64Image' => $base64Image,
            'nameX' => $nameX,
            'nameY' => $nameY,
            'nameAlign' => $nameAlign,
            'serialX' => $serialX,
            'serialY' => $serialY,
            'serialAlign' => $serialAlign,
        ])->setPaper('A4', 'landscape');

        // 6. Download PDF langsung
        return $pdf->download('Sertifikat - ' . $participant->name . '.pdf');
    }
}