@extends('layouts.admin')

@section('title', 'Dashboard Event - Serat Admin')
@section('header', 'Kontrol Panel Acara')

@section('content')

<!-- BUNGKUSAN UTAMA DAFTAR (LIST) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden divide-y divide-gray-100">

    <!-- BARIS 1: UPLOAD DATA -->
    <div class="p-6 md:p-8 flex flex-col lg:flex-row gap-6 lg:gap-12 hover:bg-gray-50 transition-colors items-start">
        <div class="lg:w-1/3">
            <h2 class="text-lg font-bold flex items-center gap-3 mb-2">
                <span class="bg-blue-100 text-blue-700 w-10 h-10 rounded-lg flex items-center justify-center shrink-0"><i class="fa-solid fa-file-csv"></i></span>
                Data Peserta
            </h2>
            <p class="text-sm text-gray-600 leading-relaxed">Total saat ini: <strong class="text-blue-600 text-base">{{ $participantsCount }}</strong> orang.</p>
            <p class="text-xs text-gray-400 mt-1">Format CSV: [No WhatsApp], [Nama Lengkap] tanpa header.</p>
        </div>
        <div class="lg:w-2/3 w-full flex flex-col sm:flex-row gap-3">
            <form action="{{ route('admin.upload-data') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-3">
                @csrf
                <input type="file" name="file" accept=".csv,.txt" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors border border-gray-200 rounded-lg bg-white">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm shadow-sm">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Proses File CSV
                </button>
            </form>
            <form action="{{ route('admin.clear-data') }}" method="POST" class="sm:w-auto w-full" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA data peserta?');">
                @csrf
                <button type="submit" class="w-full h-full bg-white text-red-500 font-bold py-2.5 px-4 rounded-lg border border-red-200 hover:bg-red-50 hover:border-red-300 transition-colors text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-trash-can"></i> Kosongkan
                </button>
            </form>
        </div>
    </div>

    <!-- BARIS 2: UPLOAD TEMPLATE -->
    <div class="p-6 md:p-8 flex flex-col lg:flex-row gap-6 lg:gap-12 hover:bg-gray-50 transition-colors items-start">
        <div class="lg:w-1/3">
            <h2 class="text-lg font-bold flex items-center gap-3 mb-2">
                <span class="bg-green-100 text-green-700 w-10 h-10 rounded-lg flex items-center justify-center shrink-0"><i class="fa-solid fa-image"></i></span>
                Template Sertifikat
            </h2>
            @if($template)
                <div class="inline-flex items-center gap-1.5 text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full mt-1">
                    <i class="fa-solid fa-circle-check"></i> Terpasang & Aktif
                </div>
            @else
                <div class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-100 px-3 py-1 rounded-full mt-1">
                    <i class="fa-solid fa-triangle-exclamation"></i> Belum Ada Template
                </div>
            @endif
            <p class="text-xs text-gray-400 mt-3">Gunakan resolusi lanskap A4 (JPG/PNG).</p>
        </div>
        <div class="lg:w-2/3 w-full flex flex-col sm:flex-row gap-3">
            <form action="{{ route('admin.upload-template') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-3">
                @csrf
                <input type="file" name="template" accept="image/jpeg,image/png,image/jpg" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:border-0 file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors border border-gray-200 rounded-lg bg-white">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-emerald-700 transition-colors text-sm shadow-sm">
                    <i class="fa-solid fa-upload mr-2"></i> Terapkan Template
                </button>
            </form>
            @if($template)
            <form action="{{ route('admin.clear-template') }}" method="POST" class="sm:w-auto w-full" onsubmit="return confirm('Hapus template saat ini?');">
                @csrf
                <button type="submit" class="w-full h-full bg-white text-orange-500 font-bold py-2.5 px-4 rounded-lg border border-orange-200 hover:bg-orange-50 hover:border-orange-300 transition-colors text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-eraser"></i> Hapus
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- BARIS 3: AKSES PORTAL -->
    <div class="p-6 md:p-8 flex flex-col lg:flex-row gap-6 lg:gap-12 hover:bg-gray-50 transition-colors items-center">
        <div class="lg:w-1/3">
            <h2 class="text-lg font-bold flex items-center gap-3 mb-2">
                <span class="bg-purple-100 text-purple-700 w-10 h-10 rounded-lg flex items-center justify-center shrink-0"><i class="fa-solid fa-toggle-on"></i></span>
                Akses Portal Unduhan
            </h2>
            <p class="text-sm text-gray-600 leading-relaxed">Buka atau tutup akses peserta ke web.</p>
        </div>
        <div class="lg:w-2/3 w-full">
            @if(isset($isOpen) && $isOpen)
            <div class="flex flex-col sm:flex-row items-center justify-between bg-green-50/50 border border-green-200 p-4 rounded-xl gap-4">
                <div>
                    <div class="text-green-700 font-extrabold text-sm mb-1 flex items-center gap-2">
                        <span class="relative flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span></span>
                        PORTAL TERBUKA
                    </div>
                    <p class="text-xs text-green-800">Peserta saat ini <strong>BISA</strong> mengunduh sertifikat.</p>
                </div>
                <form action="{{ route('admin.close-session') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Yakin ingin menutup portal?');">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-red-600 transition-colors text-sm shadow-sm whitespace-nowrap">
                        <i class="fa-solid fa-lock mr-2"></i> Tutup Akses
                    </button>
                </form>
            </div>
            @else
            <div class="flex flex-col sm:flex-row items-center justify-between bg-gray-50 border border-gray-200 p-4 rounded-xl gap-4">
                <div>
                    <div class="text-gray-600 font-extrabold text-sm mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i> PORTAL TERTUTUP
                    </div>
                    <p class="text-xs text-gray-500">Peserta <strong>TIDAK BISA</strong> mengunduh sertifikat.</p>
                </div>
                <form action="{{ route('admin.open-session') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-green-600 transition-colors text-sm shadow-sm whitespace-nowrap">
                        <i class="fa-solid fa-unlock-keyhole mr-2"></i> Buka Akses
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- BARIS 4: FORMAT NOMOR -->
    <div class="p-6 md:p-8 flex flex-col lg:flex-row gap-6 lg:gap-12 hover:bg-gray-50 transition-colors items-center">
        <div class="lg:w-1/3">
            <h2 class="text-lg font-bold flex items-center gap-3 mb-2">
                <span class="bg-amber-100 text-amber-700 w-10 h-10 rounded-lg flex items-center justify-center shrink-0"><i class="fa-solid fa-hashtag"></i></span>
                Format Nomor Seri
            </h2>
            <p class="text-sm text-gray-600 leading-relaxed">Awalan untuk nomor urut otomatis.</p>
        </div>
        <div class="lg:w-2/3 w-full">
            <form action="{{ route('admin.save-prefix') }}" method="POST" class="flex flex-col gap-2">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="prefix" value="{{ $prefixValue }}" required class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none text-sm transition-all shadow-sm">
                    <button type="submit" class="bg-amber-500 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-amber-600 transition-colors text-sm shadow-sm whitespace-nowrap">
                        <i class="fa-solid fa-save mr-2"></i> Simpan
                    </button>
                </div>
                <div class="text-xs text-amber-700 bg-amber-50/50 p-2 rounded inline-block w-max mt-1">
                    Pratinjau: <strong class="font-mono ml-1">{{ $prefixValue }}001</strong>
                </div>
            </form>
        </div>
    </div>

    <!-- BARIS 5: JUDUL ACARA -->
    <div class="p-6 md:p-8 flex flex-col lg:flex-row gap-6 lg:gap-12 hover:bg-gray-50 transition-colors items-center">
        <div class="lg:w-1/3">
            <h2 class="text-lg font-bold flex items-center gap-3 mb-2">
                <span class="bg-indigo-100 text-indigo-700 w-10 h-10 rounded-lg flex items-center justify-center shrink-0"><i class="fa-solid fa-text-width"></i></span>
                Nama / Judul Acara
            </h2>
            <p class="text-sm text-gray-600 leading-relaxed">Ubah teks statis di halaman depan portal.</p>
        </div>
        <div class="lg:w-2/3 w-full">
            <form action="{{ route('admin.save-seminar-title') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="text" name="title" value="{{ $seminarTitle ?? 'SCAR 2026' }}" required placeholder="Contoh: SEMINAR IT 2027" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-all shadow-sm">
                <button type="submit" class="bg-indigo-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-indigo-700 transition-colors text-sm shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-save mr-2"></i> Simpan
                </button>
            </form>
        </div>
    </div>

</div>
@endsection