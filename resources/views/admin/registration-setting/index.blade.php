<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setting Registrasi & Background') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Pengaturan Utama -->
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Pengaturan Umum Form Pendaftaran</h3>
                <form action="{{ route('admin.registration-settings.update-general') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Form Registrasi</label>
                                <input type="text" name="form_title" value="{{ $formTitle }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Form Registrasi</label>
                                <select name="registration_open" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="1" {{ $isOpen == '1' ? 'selected' : '' }}>Buka (Bisa Menerima Peserta)</option>
                                    <option value="0" {{ $isOpen == '0' ? 'selected' : '' }}>Tutup</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Background Acara (Untuk Di-download)</label>
                                @if($eventBackground)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($eventBackground) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline text-sm font-semibold">Lihat Background Saat Ini</a>
                                    </div>
                                @endif
                                <input type="file" name="event_background" class="w-full border-gray-300 rounded-md shadow-sm p-1 border" accept="image/jpeg,image/png">
                                <small class="text-gray-500">Kosongkan jika tidak ingin mengubah background yang sudah ada.</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-right">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Simpan Pengaturan Umum</button>
                    </div>
                </form>
            </div>

            <!-- Form Builder Dinamis -->
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border">
                <h3 class="text-lg font-bold mb-2 border-b pb-2">Pembuat Kolom Isian Tambahan (Dinamis)</h3>
                <p class="text-sm text-gray-600 mb-6 bg-blue-50 p-3 rounded border border-blue-200">
                    <strong>Catatan:</strong> Kolom <b>Nama Lengkap</b> dan <b>Nomor WhatsApp</b> sudah otomatis ada di form peserta. Gunakan fitur di bawah ini hanya untuk membuat isian <i>tambahan</i> (misal: Instansi, Umur, Alamat).
                </p>
                
                <form action="{{ route('admin.registration-settings.fields.store') }}" method="POST" class="mb-6 bg-gray-50 p-4 border rounded shadow-inner">
                    @csrf
                    <div class="flex flex-col md:flex-row items-end space-y-4 md:space-y-0 md:space-x-4">
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kolom / Pertanyaan</label>
                            <input type="text" name="label" placeholder="Misal: Asal Instansi" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="w-full md:w-64">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Jawaban</label>
                            <select name="type" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="text">Teks Bebas (Huruf & Angka)</option>
                                <option value="number">Hanya Angka (Nomor)</option>
                            </select>
                        </div>
                        <div class="w-full md:w-auto">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">Tambah Kolom</button>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Label Pertanyaan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Tipe Isian</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($fields as $field)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $field->label }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $field->type == 'text' ? 'Teks Bebas' : 'Hanya Angka' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('admin.registration-settings.fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Menghapus kolom ini juga akan menghapus data jawaban peserta untuk kolom ini. Lanjutkan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-6 text-center text-gray-500 bg-gray-50">Belum ada kolom isian tambahan yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>