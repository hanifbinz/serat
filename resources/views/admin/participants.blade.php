@extends('layouts.admin')

@section('title', 'Manajemen Peserta - Serat Admin')
@section('header', 'Manajemen Data Peserta')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <form method="GET" action="{{ route('admin.participants.index') }}" class="w-full sm:w-auto flex gap-2">
            <input type="text" name="search" placeholder="Cari Nama / No. WA..." value="{{ request('search') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none w-full sm:w-64">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            @if(request('search'))
            <a href="{{ route('admin.participants.index') }}" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-300 transition-colors flex items-center">
                Reset
            </a>
            @endif
        </form>

        <button onclick="document.getElementById('modalAdd').classList.remove('hidden')" class="w-full sm:w-auto bg-emerald-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2 shadow-sm">
            <i class="fa-solid fa-user-plus"></i> Tambah Peserta Baru
        </button>
    </div>

    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-xs border-b">
                <tr>
                    <th class="p-4 w-16">No</th>
                    <th class="p-4">No. WhatsApp / ID</th>
                    <th class="p-4">Nama Lengkap</th>
                    <th class="p-4 text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($participants as $key => $p)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="p-4 font-medium text-gray-500">{{ $participants->firstItem() + $key }}</td>
                    <td class="p-4 font-mono font-bold text-blue-600">{{ $p->nim }}</td>
                    <td class="p-4 font-semibold text-gray-800">{{ $p->name }}</td>
                    <td class="p-4">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="editData('{{ $p->id }}', '{{ $p->nim }}', '{{ addslashes($p->name) }}')" class="bg-amber-100 text-amber-700 px-3 py-1.5 rounded-md hover:bg-amber-200 transition-colors text-xs font-bold flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.participants.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus peserta ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-600 px-3 py-1.5 rounded-md hover:bg-red-200 transition-colors text-xs font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400">
                        <i class="fa-solid fa-users-slash text-3xl mb-2 block"></i>
                        Belum ada data peserta.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $participants->links() }}
    </div>
</div>

<!-- Modal Tambah Peserta -->
<div id="modalAdd" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="bg-slate-900 text-white p-4 px-6 flex justify-between items-center">
            <h3 class="font-bold text-base"><i class="fa-solid fa-user-plus text-amber-500 mr-2"></i> Tambah Peserta Baru</h3>
            <button onclick="document.getElementById('modalAdd').classList.add('hidden')" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('admin.participants.store') }}" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">No. WhatsApp / ID Peserta</label>
                <input type="text" name="nim" required placeholder="Contoh: 08123456789" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nama Lengkap + Gelar</label>
                <input type="text" name="name" required placeholder="Contoh: Dr. Budi Santoso, M.Kom" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalAdd').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Form Hidden untuk Update JS -->
<form id="formEdit" method="POST" class="hidden">
    @csrf
    @method('PUT')
    <input type="hidden" name="nim" id="editNim">
    <input type="hidden" name="name" id="editName">
</form>

<script>
    function editData(id, nim, name) {
        let newNim = prompt("Ubah No. WhatsApp / ID:", nim);
        if (newNim === null) return;
        
        let newName = prompt("Ubah Nama Lengkap:", name);
        if (newName === null) return;

        if (newNim.trim() === '' || newName.trim() === '') {
            alert('Semua kolom wajib diisi!');
            return;
        }

        let form = document.getElementById('formEdit');
        form.action = '/admin/participants/' + id;
        document.getElementById('editNim').value = newNim;
        document.getElementById('editName').value = newName;
        form.submit();
    }
</script>
@endsection