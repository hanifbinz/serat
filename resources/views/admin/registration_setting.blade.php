@extends('layouts.admin')

@section('header', 'Pengaturan Form Pendaftaran')

@section('content')
<div class="bg-white p-8 shadow-sm rounded-lg border border-gray-200 max-w-4xl">
    <form action="{{ route('admin.registration-settings.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">
                <i class="fa-solid fa-gear text-slate-400 mr-2"></i> Konfigurasi Umum
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Event</label>
                    <input type="text" name="event_name" value="{{ $settings['event_name'] ?? '' }}" placeholder="Masukkan Nama Event" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 px-4 py-2 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status Pendaftaran</label>
                    <select name="is_open" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 px-4 py-2 outline-none bg-white">
                        <option value="1" {{ ($settings['is_open'] ?? '1') == '1' ? 'selected' : '' }}>Dibuka</option>
                        <option value="0" {{ ($settings['is_open'] ?? '1') == '0' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-colors">
                <i class="fa-solid fa-save mr-1"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection