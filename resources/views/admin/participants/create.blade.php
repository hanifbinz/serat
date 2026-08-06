@extends('layouts.admin')

@section('header', 'Tambah Peserta')

@section('content')
<div class="max-w-2xl bg-white p-8 shadow-sm rounded-lg border border-gray-200">
    <div class="mb-6">
        <a href="{{ route('admin.participants.index') }}" class="text-gray-500 hover:text-blue-600 text-sm font-medium transition-colors">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Data Peserta
        </a>
    </div>

    <form action="{{ route('admin.participants.store') }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Muhammad Hanif" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2 outline-none transition-all" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2 outline-none transition-all" required>
            <span class="text-xs text-gray-500 mt-1 block">* Digunakan untuk login dan download sertifikat.</span>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email Aktif (Opsional)</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: hanif@gmail.com" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2 outline-none transition-all">
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-colors">
                <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection