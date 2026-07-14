<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Serat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-amber-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            <h1 class="text-xl font-bold">Serat Admin Panel</h1>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-red-600 font-semibold hover:text-red-800 transition-colors">Logout</button>
        </form>
    </nav>

    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <!-- Pesan Sukses/Error -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        @if($errors->any())
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- KARD 1: Upload Data -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center">1</span>
                    Upload Data Peserta (CSV)
                </h2>
                
                <p class="mb-4 text-gray-600">Total Peserta Saat Ini: <span class="font-bold text-blue-600 text-lg">{{ $participantsCount }}</span> orang</p>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 text-sm text-blue-800">
                    <p class="font-bold">Format CSV harus 2 kolom tanpa header:</p>
                    <p>[No WhatsApp], [Nama Lengkap].</p>
                    <p class="italic text-gray-500">Contoh: 081234567890, Budi Santoso</p>
                </div>

                <form action="{{ route('admin.upload-data') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4 border border-gray-300 rounded-md overflow-hidden bg-white">
                        <input type="file" name="file" accept=".csv,.txt" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                        Proses Data Peserta
                    </button>
                </form>

                <form action="{{ route('admin.clear-data') }}" method="POST" class="mt-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA data peserta? Nomor urut akan kembali ke 1.');">
                    @csrf
                    <button type="submit" class="w-full bg-red-50 text-red-600 font-bold py-2.5 px-4 rounded-lg border border-red-200 hover:bg-red-100 hover:text-red-700 transition-colors">
                        🗑️ Kosongkan Semua Data Peserta
                    </button>
                </form>
            </div>

            <!-- KARD 2: Upload Template -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="bg-green-100 text-green-700 w-8 h-8 rounded-full flex items-center justify-center">2</span>
                    Upload Template Sertifikat
                </h2>

                @if($template)
                <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-lg mb-4 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <strong>Status:</strong> Template gambar sudah terpasang.
                </div>
                @else
                <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg mb-4 text-sm">
                    <strong>Status:</strong> Belum ada template terpasang.
                </div>
                @endif

                <div class="bg-gray-50 border border-gray-200 p-4 mb-4 text-sm text-gray-600 rounded-lg">
                    Gunakan gambar resolusi lanskap A4 dengan format <strong>JPG</strong> atau <strong>PNG</strong>.
                </div>

                <form action="{{ route('admin.upload-template') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4 border border-gray-300 rounded-md overflow-hidden bg-white">
                        <input type="file" name="template" accept="image/jpeg,image/png,image/jpg" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-emerald-700 transition-colors">
                        Upload & Terapkan Template
                    </button>
                </form>

                @if($template)
                <form action="{{ route('admin.clear-template') }}" method="POST" class="mt-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template saat ini?');">
                    @csrf
                    <button type="submit" class="w-full bg-orange-50 text-orange-600 font-bold py-2.5 px-4 rounded-lg border border-orange-200 hover:bg-orange-100 hover:text-orange-700 transition-colors">
                        ❌ Hapus Template Saat Ini
                    </button>
                </form>
                @endif
            </div>
            
            <!-- KARD 3: Manajemen Presensi -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="bg-purple-100 text-purple-700 w-8 h-8 rounded-full flex items-center justify-center">3</span>
                    Manajemen Presensi (Check-In)
                </h2>

                <p class="text-sm text-gray-600 mb-4">Fitur ini mengunci sertifikat. Peserta wajib mengisi form check-in agar bisa mengunduh sertifikatnya.</p>

                @if($activeLink)
                <div class="bg-purple-50 border border-purple-200 p-5 rounded-lg mb-4">
                    <p class="text-sm font-bold text-purple-800 mb-2">🔴 Sesi Absensi Sedang Dibuka!</p>
                    <p class="text-xs text-gray-600 mb-1">Berikan URL di bawah ini kepada peserta (atau jadikan QR Code di proyektor):</p>
                    
                    <div class="bg-white p-3 border border-gray-300 rounded text-center my-3 select-all">
                        <a href="https://sertifikat.majuterus.my.id/checkin/{{ $activeLink->value }}" target="_blank" class="font-mono text-blue-600 font-bold break-all">
                            https://sertifikat.majuterus.my.id/checkin/{{ $activeLink->value }}
                        </a>
                    </div>
                    
                    <form action="{{ route('admin.close-link') }}" method="POST" onsubmit="return confirm('Yakin ingin menutup sesi absen ini? Link akan langsung hangus.');">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-red-600 transition-colors text-sm">
                            Tutup Sesi Absensi
                        </button>
                    </form>
                </div>
                @else
                <div class="bg-gray-50 border border-gray-200 p-5 rounded-lg text-center">
                    <p class="text-sm text-gray-500 mb-4">Belum ada sesi absensi yang aktif. Sertifikat peserta saat ini terkunci.</p>
                    <form action="{{ route('admin.generate-link') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-purple-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                            Buat Link Check-In Baru
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- KARD 4: FORMAT NOMOR SERTIFIKAT (BARU) -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="bg-amber-100 text-amber-700 w-8 h-8 rounded-full flex items-center justify-center">4</span>
                    Format Nomor Sertifikat
                </h2>

                <p class="text-sm text-gray-600 mb-4">Atur awalan teks untuk nomor seri sertifikat. Nomor urut/ID peserta akan ditambahkan secara otomatis di belakangnya.</p>

                <form action="{{ route('admin.save-prefix') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Awalan Nomor (Prefix)</label>
                        <input type="text" name="prefix" value="{{ $prefixValue }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50" placeholder="Contoh: SCAR/2026/VI/">
                    </div>
                    
                    <div class="bg-amber-50 border border-amber-200 p-3 rounded-lg mb-4 text-sm text-amber-800">
                        Pratinjau Hasil: <br>
                        <strong class="font-mono text-lg">{{ $prefixValue }}001</strong>
                    </div>

                    <button type="submit" class="w-full bg-amber-500 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-amber-600 transition-colors">
                        Simpan Format Nomor
                    </button>
                </form>
            </div>

        </div>
    </div>
</body>
</html>