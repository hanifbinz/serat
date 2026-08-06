@extends('layouts.admin')
@section('header', 'Dashboard Event')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Header Card -->
    <div class="p-6 border-b border-slate-100 bg-white flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
            <i class="fa-solid fa-images text-xl"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-800">Galeri & Dokumentasi Acara</h3>
            <p class="text-sm text-slate-500">Area khusus untuk mengelola media dan foto-foto kegiatan.</p>
        </div>
    </div>
    
    <!-- Body Card -->
    <div class="p-8">
        <div class="border-2 border-dashed border-slate-300 rounded-xl h-72 flex flex-col items-center justify-center bg-slate-50 text-slate-400 hover:bg-slate-100 hover:border-indigo-300 transition-colors group cursor-pointer">
            <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400 group-hover:text-indigo-500"></i>
            </div>
            <span class="font-semibold text-slate-600">Area Dokumentasi (Segera Hadir)</span>
            <span class="text-xs mt-2">Fitur ini sedang dalam tahap pengembangan</span>
        </div>
    </div>
</div>
@endsection