@extends('layouts.admin')
@section('header', 'Kelola Data Peserta')

@section('content')
<!-- Header & Aksi Atas -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    
    <!-- Form Search -->
    <form action="{{ route('admin.participants.index') }}" method="GET" class="w-full md:w-auto flex flex-1 max-w-md">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, no wa, atau email..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-r-lg text-sm font-semibold transition-colors">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.participants.index') }}" class="ml-2 px-4 py-2.5 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 rounded-lg text-sm font-semibold transition-colors flex items-center">
                <i class="fa-solid fa-xmark mr-1"></i> Reset
            </a>
        @endif
    </form>

    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <!-- Tombol Tambah Manual -->
        <a href="{{ route('admin.participants.create') }}" class="flex-1 md:flex-none bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg text-sm shadow-sm transition-all text-center">
            <i class="fa-solid fa-plus mr-1"></i> Tambah Manual
        </a>

        <!-- Tombol Import CSV -->
        <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="flex-1 md:flex-none bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-semibold py-2 px-4 rounded-lg text-sm shadow-sm transition-all">
            <i class="fa-solid fa-file-import mr-1"></i> Import CSV
        </button>
        
        <!-- Tombol Kosongkan Data -->
        <form action="{{ route('admin.participants.truncate') }}" method="POST" onsubmit="return confirm('Peringatan: Ini akan menghapus SEMUA data peserta. Anda yakin?');" class="flex-1 md:flex-none">
            @csrf
            <button type="submit" class="w-full bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 font-semibold py-2 px-4 rounded-lg text-sm transition-all">
                <i class="fa-solid fa-trash-can mr-1"></i> Kosongkan
            </button>
        </form>
    </div>
</div>

<!-- Card Tabel Data Peserta -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="text-xs text-slate-500 uppercase tracking-wider bg-slate-50 border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Nama Peserta</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Kontak</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">Data Tambahan</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($participants as $index => $participant)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-center text-slate-400 font-medium">
                        {{ $participants->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">{{ $participant->name }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="flex items-center gap-2 text-slate-600"><i class="fa-brands fa-whatsapp text-emerald-500"></i> {{ $participant->phone ?? '-' }}</p>
                        <p class="flex items-center gap-2 text-slate-500 text-xs mt-1"><i class="fa-regular fa-envelope"></i> {{ $participant->email ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($participant->answers && $participant->answers->count() > 0)
                            <button onclick="document.getElementById('detailModal-{{ $participant->id }}').classList.remove('hidden')" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-semibold text-xs bg-indigo-50 px-3 py-1.5 rounded-full transition-colors">
                                <i class="fa-solid fa-eye"></i> Lihat Data ({{ $participant->answers->count() }})
                            </button>
                        @else
                            <span class="inline-flex items-center gap-1 text-slate-400 bg-slate-50 px-3 py-1.5 rounded-full text-xs border border-slate-200">
                                Kosong
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Tombol Unduh Sertifikat -->
                            <a href="{{ route('admin.participants.download-cert', $participant->id) }}" title="Unduh Sertifikat" class="w-8 h-8 flex items-center justify-center text-emerald-600 bg-emerald-50 rounded-lg border border-emerald-200 hover:bg-emerald-100 hover:text-emerald-700 transition-colors">
                                <i class="fa-solid fa-certificate"></i>
                            </a>
                            
                            <!-- Tombol Edit -->
                            <a href="{{ route('admin.participants.edit', $participant->id) }}" title="Edit" class="w-8 h-8 flex items-center justify-center text-indigo-600 bg-indigo-50 rounded-lg border border-indigo-200 hover:bg-indigo-100 hover:text-indigo-700 transition-colors">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            
                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.participants.destroy', $participant->id) }}" method="POST" onsubmit="return confirm('Hapus peserta {{ $participant->name }}?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" class="w-8 h-8 flex items-center justify-center text-rose-600 bg-rose-50 rounded-lg border border-rose-200 hover:bg-rose-100 hover:text-rose-700 transition-colors">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
                            <p class="font-medium text-slate-500">
                                @if(request('search')) Data peserta "{{ request('search') }}" tidak ditemukan.
                                @else Belum ada data peserta yang mendaftar.
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($participants instanceof \Illuminate\Pagination\LengthAwarePaginator && $participants->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
        {{ $participants->appends(['search' => request('search')])->links() }}
    </div>
    @endif
</div>

<!-- Modal Import CSV -->
<div id="importModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
    <div class="relative w-full max-w-md shadow-2xl rounded-2xl bg-white overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Import Data CSV</h3>
            <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.participants.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih File CSV</label>
                    <input type="file" name="file" accept=".csv" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-lg cursor-pointer">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-colors text-sm">
                        Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Data Peserta (Tetap menggunakan iterasi Anda, hanya di-styling ulang) -->
@foreach($participants as $participant)
    @if($participant->answers && $participant->answers->count() > 0)
    <div id="detailModal-{{ $participant->id }}" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
        <div class="relative w-full max-w-md shadow-2xl rounded-2xl bg-white overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800">Data Tambahan</h3>
                <button onclick="document.getElementById('detailModal-{{ $participant->id }}').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Nama Peserta</p>
                    <p class="text-base font-bold text-slate-900">{{ $participant->name }}</p>
                </div>
                
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 max-h-64 overflow-y-auto custom-scrollbar">
                    <ul class="divide-y divide-slate-200">
                        @foreach($participant->answers as $answer)
                            <li class="py-3 flex flex-col first:pt-0 last:pb-0">
                                <span class="text-xs font-semibold text-slate-500">{{ $answer->field->name ?? $answer->field->label ?? 'Data' }}</span>
                                <span class="text-sm font-medium text-slate-900 mt-1">{{ $answer->answer_value ?? '-' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection