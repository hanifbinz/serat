@extends('layouts.admin')
@section('title', 'Manajemen User - Serat')
@section('header', 'Pengaturan Akses Pengguna')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Kolom Kiri: Form Tambah User -->
    <div class="lg:col-span-1 h-fit bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
                Tambah Pengguna
            </h2>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-slate-50 focus:bg-white transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email (Untuk Login)</label>
                <input type="email" name="email" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-slate-50 focus:bg-white transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Password</label>
                <input type="password" name="password" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-slate-50 focus:bg-white transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Hak Akses (Role)</label>
                <select name="role" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-white transition-colors">
                    <option value="admin">Admin (Tanpa Akses Menu Ini)</option>
                    <option value="administrator">Administrator (Akses Penuh)</option>
                </select>
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-4 rounded-lg transition-colors shadow-sm text-sm">
                    Simpan Pengguna Baru
                </button>
            </div>
        </form>
    </div>

    <!-- Kolom Kanan: Tabel Daftar User -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
                Daftar Pengguna Sistem
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-500 uppercase tracking-wider bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Profil User</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800 text-base">{{ $user->name }}</p>
                            <p class="text-slate-500 text-xs mt-0.5"><i class="fa-regular fa-envelope mr-1"></i> {{ $user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'administrator')
                                <span class="bg-violet-50 text-violet-700 border border-violet-200 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-crown mr-1"></i> Administrator
                                </span>
                            @else
                                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider">
                                    Admin
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-rose-600 bg-rose-50 rounded-lg border border-rose-200 hover:bg-rose-100 hover:text-rose-800 transition-colors" title="Hapus User">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                            @else
                            <span class="inline-flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-200 px-3 py-1.5 rounded-lg text-xs font-semibold">
                                <i class="fa-solid fa-circle-check mr-1"></i> Anda
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection