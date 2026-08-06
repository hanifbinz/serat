@extends('layouts.admin')
@section('header', 'Pengaturan Registrasi')

@section('content')
<div class="space-y-8">
    
    <!-- BAGIAN 1: PENGATURAN UMUM REGISTRASI -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-globe text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Pengaturan Umum Pendaftaran</h2>
                    <p class="text-sm text-slate-500">Atur judul form, status, dan background halaman publik.</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.registration-settings.update-general') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Form -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Form Pendaftaran</label>
                    <input type="text" name="form_title" value="{{ $formTitle ?? '' }}" placeholder="Contoh: Pendaftaran Seminar Nasional 2024" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                <!-- Status Pendaftaran -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status Pendaftaran</label>
                    <select name="registration_open" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-white">
                        <option value="1" {{ (isset($registrationOpen) && $registrationOpen == '1') ? 'selected' : '' }}>🟢 BUKA (Menerima Peserta)</option>
                        <option value="0" {{ (isset($registrationOpen) && $registrationOpen == '0') ? 'selected' : '' }}>🔴 TUTUP (Form Dimatikan)</option>
                    </select>
                </div>

                <!-- Link URL Dinamis & QR Code -->
                <div class="md:col-span-2 flex flex-col md:flex-row gap-6 bg-slate-50 p-5 rounded-xl border border-slate-200">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Link URL Pendaftaran (Custom Slug)</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-slate-300 bg-slate-200 text-slate-600 text-sm font-medium">
                                {{ url('/register/') }}/
                            </span>
                            <input type="text" name="registration_slug" value="{{ \App\Models\Setting::getValue('registration_slug', 'scarhub/2026/viii') }}" placeholder="contoh: event-2026" class="flex-1 rounded-r-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-mono text-indigo-700">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2"><i class="fa-solid fa-circle-info mr-1"></i> Gunakan huruf kecil, angka, atau strip (-). Hindari spasi.</p>
                        
                        <div class="mt-4 text-sm">
                            <span class="text-slate-600 font-medium">Link Aktif Anda:</span><br>
                            <a href="{{ url('/register/' . \App\Models\Setting::getValue('registration_slug', 'scarhub/2026/viii')) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium break-all flex items-center gap-1 mt-1">
                                {{ url('/register/' . \App\Models\Setting::getValue('registration_slug', 'scarhub/2026/viii')) }} <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- QR Code Generator -->
                    <div class="flex-shrink-0 flex flex-col items-center justify-center">
                        <div class="w-32 h-32 bg-white border border-slate-200 p-2 rounded-xl shadow-sm">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/register/' . \App\Models\Setting::getValue('registration_slug', 'scarhub/2026/viii'))) }}" alt="QR Code" class="w-full h-full object-contain rounded">
                        </div>
                        <span class="text-xs text-slate-500 font-medium mt-2">Scan untuk Mendaftar</span>
                    </div>
                </div>

                <!-- Background Event -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Background Form (Opsional)</label>
                    <input type="file" name="event_background" accept="image/*" class="w-full md:w-2/3 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-lg cursor-pointer bg-slate-50 mb-3">
                    
                    @if($eventBackground)
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl inline-block w-full md:w-2/3">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Preview Background Saat Ini:</label>
                            <div class="bg-slate-300 p-1.5 rounded-lg inline-block shadow-inner w-full max-w-sm">
                                <img src="{{ asset($eventBackground) }}?v={{ time() }}" alt="Event Background" class="rounded-md w-full h-32 object-cover border border-white/50">
                            </div>
                        </div>
                    @endif
                    <p class="text-[11px] text-slate-500 mt-1"><i class="fa-solid fa-circle-info mr-1"></i> Format disarankan: JPG, PNG. Maksimal 2MB. Resolusi 1920x1080px.</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan Umum
                </button>
            </div>
        </form>
    </div>

    <!-- BAGIAN 2: PENGATURAN KOLOM DINAMIS -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-indigo-600"></i> Atur Kolom Pendaftaran Tambahan
            </h2>
            <p class="text-sm text-slate-500 mt-1">Kolom Nama Lengkap, WhatsApp, dan Email sudah menjadi bawaan. Tambahkan kolom lain di bawah ini.</p>
        </div>

        <div class="p-6">
            <!-- Form Tambah Kolom -->
            <form action="{{ route('admin.registration-settings.fields.store') }}" method="POST" class="bg-indigo-50/50 p-5 rounded-xl border border-indigo-100 mb-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Kolom (Label)</label>
                        <input type="text" name="label" required placeholder="Contoh: Asal Instansi" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipe Isian</label>
                        <select name="type" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-white">
                            <option value="text">Teks Pendek (Text)</option>
                            <option value="number">Angka Saja (Number)</option>
                            <option value="email">Email</option>
                            <option value="date">Tanggal (Date)</option>
                            <option value="textarea">Teks Panjang (Textarea)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Wajib?</label>
                        <select name="is_required" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-white">
                            <option value="1">Ya (Wajib)</option>
                            <option value="0">Tidak (Opsional)</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-4 rounded-lg shadow-sm transition-colors text-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus"></i> Tambah Kolom
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabel Daftar Kolom -->
            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-500 uppercase tracking-wider bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-center font-semibold w-16">No</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Nama Kolom (Label)</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Tipe Input</th>
                            <th scope="col" class="px-6 py-4 text-center font-semibold">Status</th>
                            <th scope="col" class="px-6 py-4 text-center font-semibold w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($fields ?? [] as $index => $field)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $field->label }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-semibold px-2.5 py-1 rounded-md">{{ $field->type }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($field->is_required)
                                    <span class="text-rose-600 font-bold text-xs bg-rose-50 px-2.5 py-1 rounded-md border border-rose-100">Wajib</span>
                                @else
                                    <span class="text-slate-500 font-medium text-xs bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">Opsional</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Tombol Edit -->
                                    <button type="button" onclick="openEditModal({{ $field->id }}, '{{ addslashes($field->label) }}', '{{ $field->type }}', {{ $field->is_required }})" class="w-8 h-8 flex items-center justify-center text-indigo-600 bg-indigo-50 rounded-lg border border-indigo-200 hover:bg-indigo-100 hover:text-indigo-800 transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.registration-settings.fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kolom ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-rose-600 bg-rose-50 rounded-lg border border-rose-200 hover:bg-rose-100 hover:text-rose-800 transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-medium">
                                Belum ada kolom dinamis yang ditambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kolom Dinamis -->
<div id="editFieldModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
    <div class="relative w-full max-w-md shadow-2xl rounded-2xl bg-white overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Edit Kolom</h3>
            <button type="button" onclick="document.getElementById('editFieldModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <form id="editFieldForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Kolom (Label)</label>
                <input type="text" name="label" id="edit_label" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipe Isian</label>
                <select name="type" id="edit_type" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-white">
                    <option value="text">Teks Pendek (Text)</option>
                    <option value="number">Angka Saja (Number)</option>
                    <option value="email">Email</option>
                    <option value="date">Tanggal (Date)</option>
                    <option value="textarea">Teks Panjang (Textarea)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Apakah Wajib Diisi?</label>
                <select name="is_required" id="edit_is_required" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-white">
                    <option value="1">Ya (Wajib)</option>
                    <option value="0">Tidak (Opsional)</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('editFieldModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-colors text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, label, type, isRequired) {
    const form = document.getElementById('editFieldForm');
    form.action = "{{ url('admin/registration-settings/fields') }}/" + id;
    document.getElementById('edit_label').value = label;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_is_required').value = isRequired ? "1" : "0";
    document.getElementById('editFieldModal').classList.remove('hidden');
}
</script>
@endsection