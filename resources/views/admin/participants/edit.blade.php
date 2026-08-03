@extends('layouts.admin')

@section('header', 'Edit Peserta')

@section('content')
<div class="max-w-2xl bg-white p-8 shadow-sm rounded-lg border border-gray-200">
    <div class="mb-6">
        <a href="{{ route('admin.participants.index') }}" class="text-gray-500 hover:text-blue-600 text-sm font-medium transition-colors">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Data Peserta
        </a>
    </div>

    <form action="{{ route('admin.participants.update', $participant->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $participant->name) }}" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2 outline-none transition-all" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp</label>
            <input type="text" name="phone" value="{{ old('phone', $participant->phone) }}" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2 outline-none transition-all" required>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-6 rounded-lg shadow transition-colors">
                <i class="fa-solid fa-check-circle mr-1"></i> Update Data
            </button>
        </div>
    </form>
</div>
@endsection