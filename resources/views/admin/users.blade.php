@extends('layouts.admin')

@section('title', 'Manajemen User - Serat')
@section('header', 'Pengaturan Akses Pengguna')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Form Tambah User -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-1 h-fit">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center"><i class="fa-solid fa-user-plus"></i></span>
            Tambah Pengguna Baru
        </h2>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Email (Untuk Login)</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-1">Hak Akses (Role)</label>
                <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="admin">Admin Biasa (Tanpa Manajemen User)</option>
                    <option value="administrator">Administrator (Akses Penuh)</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                Simpan Pengguna Baru
            </button>
        </form>
    </div>

    <!-- Tabel Daftar User -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span class="bg-slate-100 text-slate-700 w-8 h-8 rounded-full flex items-center justify-center"><i class="fa-solid fa-list"></i></span>
            Daftar Pengguna Sistem
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-200 text-sm">
                        <th class="p-3 text-gray-600 font-semibold">Nama</th>
                        <th class="p-3 text-gray-600 font-semibold">Email</th>
                        <th class="p-3 text-gray-600 font-semibold">Role</th>
                        <th class="p-3 text-gray-600 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                        <td class="p-3 font-medium text-gray-800">{{ $user->name }}</td>
                        <td class="p-3 text-gray-600 text-sm">{{ $user->email }}</td>
                        <td class="p-3 text-sm font-bold">
                            @if($user->role === 'administrator')
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs uppercase tracking-wider">Administrator</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs uppercase tracking-wider">Admin</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-2 bg-red-50 hover:bg-red-100 rounded transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-xs text-gray-400 italic">Akun Anda</span>
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