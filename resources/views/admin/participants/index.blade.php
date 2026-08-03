<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Peserta & Import Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Panel Aksi Massal (Import CSV & Truncate) -->
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Import CSV -->
                <div class="border-r md:pr-6">
                    <h3 class="text-lg font-bold mb-2">Impor Peserta dari CSV</h3>
                    <p class="text-sm text-gray-600 mb-4">Format kolom CSV: Baris pertama Header, Kolom 1 = Nama, Kolom 2 = WhatsApp.</p>
                    <form action="{{ route('admin.participants.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="file" name="csv_file" class="w-full border border-gray-300 rounded-md p-1 shadow-sm text-sm" accept=".csv, .txt" required>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Upload & Impor CSV</button>
                    </form>
                </div>

                <!-- Kosongkan Data / Truncate -->
                <div>
                    <h3 class="text-lg font-bold mb-2 text-red-600">Zona Bahaya (Kosongkan Data)</h3>
                    <p class="text-sm text-gray-600 mb-4">Menghapus seluruh data peserta yang tersimpan di database secara permanen.</p>
                    <form action="{{ route('admin.participants.truncate') }}" method="POST" onsubmit="return confirm('PERINGATAN: Seluruh data peserta akan dihapus permanen! Lanjutkan?');">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow">Kosongkan Semua Data Peserta</button>
                    </form>
                </div>
            </div>

            <!-- Tabel Data Peserta -->
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold">Daftar Peserta Terdaftar</h3>
                    <a href="{{ route('admin.participants.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow text-sm">+ Tambah Peserta Manual</a>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Nama Lengkap</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">No WhatsApp</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Jawaban Kolom Dinamis</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($participants as $index => $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participants->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $p->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->phone }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($p->answers->count() > 0)
                                            <ul class="list-disc pl-4 space-y-1">
                                                @foreach($p->answers as $ans)
                                                    <li><b>{{ optional($ans->registrationField)->label }}:</b> {{ $ans->answer_value }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada jawaban tambahan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.participants.edit', $p->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Edit</a>
                                        <form action="{{ route('admin.participants.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus peserta ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-500 bg-gray-50">Belum ada data peserta yang terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $participants->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>