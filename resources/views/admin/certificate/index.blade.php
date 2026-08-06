@extends('layouts.admin')
@section('header', 'Pengaturan Sertifikat')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <form action="{{ route('admin.certificate.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- BAGIAN 1: PENGATURAN UMUM & PORTAL -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-gear text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Pengaturan Umum & Branding</h3>
                    <p class="text-sm text-slate-500">Atur status portal dan identitas visual acara.</p>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Portal -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status Portal Download</label>
                        <select name="certificate_open" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white transition-shadow text-sm">
                            <option value="1" {{ ($settings['certificate_open'] == '1') ? 'selected' : '' }}>🟢 BUKA (Peserta dapat mengunduh)</option>
                            <option value="0" {{ ($settings['certificate_open'] == '0') ? 'selected' : '' }}>🔴 TUTUP (Akses ditutup sementara)</option>
                        </select>
                    </div>

                    <!-- Nama Acara -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Acara</label>
                        <input type="text" name="event_name" value="{{ old('event_name', $settings['event_name']) }}" placeholder="Contoh: Webinar SCARHUB VIII" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow text-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <!-- Logo Acara -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Logo Acara (Opsional)</label>
                        <input type="file" name="event_logo" accept="image/jpeg, image/png" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-lg cursor-pointer bg-slate-50">
                        
                        @if($settings['event_logo']) 
                            <div class="mt-3 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-4">
                                <div class="bg-white border border-slate-200 p-2 rounded-lg shadow-sm">
                                    <img src="{{ asset('storage/' . str_replace('public/', '', $settings['event_logo'])) }}" alt="Preview Logo" class="h-10 w-10 object-contain">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-emerald-600 flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Logo Terpasang</p>
                                    <p class="text-xs text-slate-500 font-mono mt-0.5 truncate max-w-[150px]" title="Nama File">{{ basename($settings['event_logo']) }}</p>
                                </div>
                            </div>
                        @endif
                        <span class="text-[11px] text-slate-500 mt-2 block"><i class="fa-solid fa-circle-info mr-1"></i> Biarkan kosong jika tidak ingin mengubah logo.</span> 
                    </div>

                    <!-- Marquee Text -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Teks Berjalan (Marquee)</label>
                        <textarea name="marquee_text" rows="3" placeholder="Informasi berjalan di atas portal..." class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow text-sm">{{ old('marquee_text', $settings['marquee_text']) }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Format Prefix Nomor Seri</label>
                    <div class="flex items-center">
                        <input type="text" name="certificate_serial_format" value="{{ old('certificate_serial_format', $settings['certificate_serial_format']) }}" placeholder="Contoh: SCAR/2026/VIII/" class="w-full md:w-1/2 border border-slate-300 rounded-l-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow font-mono text-indigo-700 text-sm" required>
                        <span class="bg-slate-100 border border-l-0 border-slate-300 px-4 py-2.5 rounded-r-lg text-slate-500 font-mono text-sm">063</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2"><i class="fa-solid fa-circle-info mr-1"></i> Sistem otomatis menambahkan 3 digit angka unik di belakang prefix.</p>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: DESAIN & KOORDINAT -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-violet-100 text-violet-600 flex items-center justify-center">
                    <i class="fa-solid fa-image text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Desain & Koordinat Cetak PDF</h3>
                    <p class="text-sm text-slate-500">Konfigurasi background sertifikat dan posisi teks.</p>
                </div>
            </div>
            
            <div class="p-6 space-y-8">
                <!-- Upload Background -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Template Background Sertifikat (JPG/PNG)</label>
                    <input type="file" name="certificate_bg" accept="image/jpeg, image/png" class="w-full md:w-2/3 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 border border-slate-200 rounded-lg cursor-pointer bg-slate-50">
                    
                    @if($settings['certificate_template'])
                        <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center shadow-sm w-full md:w-2/3 gap-4">
                            <div class="bg-white border border-slate-200 p-1 rounded-lg shadow-sm">
                                <img src="{{ asset('storage/' . str_replace('public/', '', $settings['certificate_template'])) }}" alt="Preview Template" class="h-16 w-24 object-cover rounded">
                            </div>
                            <div>
                                <p class="text-sm font-bold text-emerald-600 flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Template Aktif Terpasang</p>
                                <p class="text-xs text-slate-500 font-mono mt-1" title="Nama File">{{ basename($settings['certificate_template']) }}</p>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2"><i class="fa-solid fa-circle-info mr-1"></i> Biarkan kosong jika tidak ingin mengubah template.</p>
                    @else
                        <div class="mt-3 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-200">
                            <i class="fa-solid fa-circle-info"></i> Belum ada template yang diunggah.
                        </div>
                    @endif
                </div>
                
                <!-- Pengaturan Koordinat -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-xl border border-slate-200">
                    
                    <!-- Kordinat Nama -->
                    <div class="bg-white p-5 rounded-xl border-l-4 border-l-indigo-500 shadow-sm">
                        <h4 class="font-bold text-sm text-indigo-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-user-tag"></i> POSISI NAMA PESERTA</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5">Kordinat X (Kiri ke Kanan)</label>
                                <input type="number" name="name_x" value="{{ old('name_x', $settings['name_x']) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5">Kordinat Y (Atas ke Bawah)</label>
                                <input type="number" name="name_y" value="{{ old('name_y', $settings['name_y']) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5">Rata Teks (Alignment)</label>
                                <select name="name_align" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-white">
                                    <option value="center" {{ (isset($settings['name_align']) && $settings['name_align'] == 'center') ? 'selected' : '' }}>Otomatis Rata Tengah</option>
                                    <option value="left" {{ (isset($settings['name_align']) && $settings['name_align'] == 'left') ? 'selected' : '' }}>Ikut Kordinat X (Kiri)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Kordinat Serial -->
                    <div class="bg-white p-5 rounded-xl border-l-4 border-l-violet-500 shadow-sm">
                        <h4 class="font-bold text-sm text-violet-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-barcode"></i> POSISI NOMOR SERI</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5">Kordinat X (Kiri ke Kanan)</label>
                                <input type="number" name="serial_x" value="{{ old('serial_x', $settings['serial_x']) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5">Kordinat Y (Atas ke Bawah)</label>
                                <input type="number" name="serial_y" value="{{ old('serial_y', $settings['serial_y']) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5">Rata Teks (Alignment)</label>
                                <select name="serial_align" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm bg-white">
                                    <option value="center" {{ (isset($settings['serial_align']) && $settings['serial_align'] == 'center') ? 'selected' : '' }}>Otomatis Rata Tengah</option>
                                    <option value="left" {{ (isset($settings['serial_align']) && $settings['serial_align'] == 'left') ? 'selected' : '' }}>Ikut Kordinat X (Kiri)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection