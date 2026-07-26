@extends('layouts.admin')

@section('title', 'Dashboard Event - Serat Admin')
@section('header', 'Kontrol Panel Acara')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- KARD 1: Upload Data -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-3">
            <span class="bg-blue-100 text-blue-700 w-10 h-10 rounded-lg flex items-center justify-center"><i class="fa-solid fa-file-csv"></i></span>
            Upload Data Peserta
        </h2>
        
        <p class="mb-4 text-gray-600">Total Peserta Saat Ini: <span class="font-bold text-blue-600 text-lg">{{ $participantsCount }}</span> orang</p>
        
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 text-sm text-blue-800 rounded-r">
            <p class="font-bold">Format CSV harus 2 kolom tanpa header:</p>
            <p class="font-mono mt-1">[No WhatsApp], [Nama Lengkap]</p>
        </div>

        <form action="{{ route('admin.upload-data') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4 border border-gray-300 rounded-lg overflow-hidden bg-white">
                <input type="file" name="file" accept=".csv,.txt" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Proses Data Peserta
            </button>
        </form>

        <form action="{{ route('admin.clear-data') }}" method="POST" class="mt-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA data peserta?');">
            @csrf
            <button type="submit" class="w-full bg-white text-red-500 font-bold py-2.5 px-4 rounded-lg border-2 border-red-100 hover:bg-red-50 hover:border-red-200 transition-colors">
                <i class="fa-solid fa-trash-can mr-2"></i> Kosongkan Semua Data
            </button>
        </form>
    </div>

    <!-- KARD 2: Upload Template -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-3">
            <span class="bg-green-100 text-green-700 w-10 h-10 rounded-lg flex items-center justify-center"><i class="fa-solid fa-image"></i></span>
            Template Sertifikat
        </h2>

        @if($template)
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-4 text-sm flex items-center gap-3 font-medium">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <span>Template gambar sudah terpasang & aktif.</span>
        </div>
        @else
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-4 text-sm flex items-center gap-3 font-medium">
            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            <span>Belum ada template terpasang.</span>
        </div>
        @endif

        <div class="bg-gray-50 border border-gray-200 p-4 mb-4 text-sm text-gray-600 rounded-lg">
            Gunakan resolusi lanskap A4 dengan format <strong>JPG/PNG</strong>.
        </div>

        <form action="{{ route('admin.upload-template') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4 border border-gray-300 rounded-lg overflow-hidden bg-white">
                <input type="file" name="template" accept="image/jpeg,image/png,image/jpg" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors">
            </div>
            <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-emerald-700 transition-colors shadow-md">
                <i class="fa-solid fa-upload mr-2"></i> Terapkan Template
            </button>
        </form>

        @if($template)
        <form action="{{ route('admin.clear-template') }}" method="POST" class="mt-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template saat ini?');">
            @csrf
            <button type="submit" class="w-full bg-white text-orange-500 font-bold py-2.5 px-4 rounded-lg border-2 border-orange-100 hover:bg-orange-50 hover:border-orange-200 transition-colors">
                <i class="fa-solid fa-eraser mr-2"></i> Hapus Template
            </button>
        </form>
        @endif
    </div>
    
<!-- KARD 3: Sistem Saklar Portal Unduhan -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-3">
            <span class="bg-purple-100 text-purple-700 w-10 h-10 rounded-lg flex items-center justify-center"><i class="fa-solid fa-toggle-on"></i></span>
            Akses Portal Sertifikat
        </h2>

        <p class="text-sm text-gray-600 mb-5">Kontrol akses peserta ke <strong>sertifikat.majuterus.my.id</strong>. Bagikan link tersebut ke grup, lalu Buka/Tutup akses dari sini.</p>

        @if(isset($isOpen) && $isOpen)
        <div class="bg-green-50 border border-green-200 p-6 rounded-xl text-center shadow-inner">
            <div class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full font-bold text-sm mb-4 animate-pulse">
                <i class="fa-solid fa-circle-check mr-1"></i> PORTAL SEDANG TERBUKA
            </div>
            <p class="text-sm text-green-800 mb-6">Peserta saat ini <strong>BISA</strong> mengunduh sertifikat.</p>
            
            <form action="{{ route('admin.close-session') }}" method="POST" onsubmit="return confirm('Yakin ingin menutup akses portal?');">
                @csrf
                <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-red-600 transition-colors shadow-md">
                    <i class="fa-solid fa-lock mr-2"></i> Tutup Portal Sekarang
                </button>
            </form>
        </div>
        @else
        <div class="bg-gray-50 border border-gray-200 p-6 rounded-xl text-center shadow-inner">
            <div class="inline-block bg-gray-200 text-gray-600 px-4 py-2 rounded-full font-bold text-sm mb-4">
                <i class="fa-solid fa-lock mr-1"></i> PORTAL TERTUTUP (DIGEMBOK)
            </div>
            <p class="text-sm text-gray-500 mb-6">Peserta saat ini <strong>TIDAK BISA</strong> mengunduh sertifikat.</p>
            
            <form action="{{ route('admin.open-session') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-600 transition-colors shadow-md">
                    <i class="fa-solid fa-unlock-keyhole mr-2"></i> Buka Portal Sekarang
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- KARD 3 (Baru): FORMAT NOMOR -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-3">
            <span class="bg-amber-100 text-amber-700 w-10 h-10 rounded-lg flex items-center justify-center"><i class="fa-solid fa-hashtag"></i></span>
            Format Nomor Seri
        </h2>

        <p class="text-sm text-gray-600 mb-5">Atur awalan teks untuk sertifikat. ID peserta otomatis ditambahkan di belakang.</p>

        <form action="{{ route('admin.save-prefix') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Awalan Nomor (Prefix)</label>
                <input type="text" name="prefix" value="{{ $prefixValue }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none bg-gray-50 transition-all">
            </div>
            
            <div class="bg-amber-50 border border-amber-200 p-4 rounded-lg mb-5 text-sm text-amber-800 text-center">
                Pratinjau Hasil: <br>
                <strong class="font-mono text-xl mt-1 block">{{ $prefixValue }}001</strong>
            </div>

            <button type="submit" class="w-full bg-amber-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-amber-600 transition-colors shadow-md">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Format
            </button>
        </form>
    </div>

</div>
@endsection