@extends('layouts.admin')

@section('title', 'Setting Registrasi Publik - Serat Admin')
@section('header', 'Pengaturan Form Registrasi Mandiri')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
    <div class="flex flex-col lg:flex-row gap-8 items-center">
        
        <div class="lg:w-1/2 w-full">
            <h2 class="text-lg font-bold flex items-center gap-3 mb-3 text-slate-800">
                <span class="bg-blue-100 text-blue-700 w-10 h-10 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-link"></i>
                </span>
                Tautan Pendaftaran Publik
            </h2>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                Bagikan tautan ini kepada peserta acara. Peserta yang mengisi form ini akan langsung tersimpan di daftar database peserta acara.
            </p>
            <div class="bg-slate-100 p-3.5 rounded-lg border border-slate-300 font-mono text-sm break-all text-slate-800 mb-3">
                https://sertifikat.majuterus.my.id/register
            </div>
            <a href="https://sertifikat.majuterus.my.id/register" target="_blank" class="inline-flex items-center gap-2 text-blue-600 text-sm font-bold hover:underline">
                <i class="fa-solid fa-up-right-from-square"></i> Uji Coba Buka Link Pendaftaran
            </a>
        </div>

        <div class="lg:w-1/2 w-full p-6 rounded-xl border {{ $isRegOpen ? 'bg-green-50/60 border-green-200' : 'bg-slate-50 border-slate-200' }} text-center">
            <h3 class="font-extrabold text-lg mb-2 {{ $isRegOpen ? 'text-green-700' : 'text-slate-600' }} flex items-center justify-center gap-2">
                <i class="fa-solid {{ $isRegOpen ? 'fa-door-open' : 'fa-door-closed' }}"></i> 
                STATUS FORM: {{ $isRegOpen ? 'DIBUKA' : 'DITUTUP' }}
            </h3>
            <p class="text-xs {{ $isRegOpen ? 'text-green-800' : 'text-slate-500' }} mb-6">
                {{ $isRegOpen ? 'Peserta BISA mengakses link dan mendaftar mandiri saat ini.' : 'Peserta TIDAK BISA mendaftar mandiri saat ini.' }}
            </p>

            <form action="{{ route('admin.registration.toggle') }}" method="POST">
                @csrf
                <button type="submit" class="px-8 py-3 font-bold rounded-lg text-white shadow-md transition-all {{ $isRegOpen ? 'bg-red-500 hover:bg-red-600' : 'bg-green-600 hover:bg-green-700' }}">
                    <i class="fa-solid {{ $isRegOpen ? 'fa-lock' : 'fa-unlock' }} mr-2"></i>
                    Ubah Menjadi {{ $isRegOpen ? 'TUTUP FORM' : 'BUKA FORM' }}
                </button>
            </form>
        </div>

    </div>
</div>
@endsection