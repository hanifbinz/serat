@extends('layouts.admin')

@section('header', 'Pengaturan Sertifikat')

@section('content')
<div class="bg-white p-8 shadow-sm rounded-lg border border-gray-200 max-w-4xl">
    <form action="{{ route('admin.certificate.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">
                <i class="fa-solid fa-image text-slate-400 mr-2"></i> Desain Sertifikat
            </h3>
            
            <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Template Background (JPG/PNG)</label>
                <input type="file" name="certificate_bg" accept="image/jpeg, image/png" class="w-full border border-gray-300 bg-white rounded-md shadow-sm px-4 py-2 mt-1 outline-none">
                <p class="text-xs text-amber-600 mt-2 font-medium">
                    <i class="fa-solid fa-circle-info"></i> Biarkan kosong jika tidak ingin mengubah background yang sudah ada.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Posisi Teks Nama (Kordinat X - Horizontal)</label>
                    <input type="number" name="name_x" value="{{ $settings['name_x'] ?? 500 }}" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 px-4 py-2 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Posisi Teks Nama (Kordinat Y - Vertikal)</label>
                    <input type="number" name="name_y" value="{{ $settings['name_y'] ?? 400 }}" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 px-4 py-2 outline-none">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-colors">
                <i class="fa-solid fa-save mr-1"></i> Simpan Desain Sertifikat
            </button>
        </div>
    </form>
</div>
@endsection