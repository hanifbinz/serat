<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Serat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animasi masuk dan keluar untuk Floating Notification */
        .toast-enter {
            animation: slideInRight 0.5s ease-out forwards;
        }
        .toast-exit {
            animation: fadeOutRight 0.5s ease-in forwards;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    @if(session('success') || session('error') || $errors->any())
        <div id="toastBox" class="fixed top-5 right-5 z-50 toast-enter">
            @if(session('success'))
                <div class="flex items-center p-4 mb-4 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-lg">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <div>
                        <span class="font-bold">Berhasil!</span> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="flex items-center p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded-lg shadow-lg">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <div>
                        <span class="font-bold">Gagal!</span> {{ session('error') ?? $errors->first() }}
                    </div>
                </div>
            @endif
        </div>

        <script>
            // Script untuk menghilangkan notifikasi secara otomatis setelah 4 detik
            setTimeout(function() {
                var toast = document.getElementById('toastBox');
                if(toast) {
                    toast.classList.remove('toast-enter');
                    toast.classList.add('toast-exit');
                    setTimeout(() => toast.remove(), 500); // Hapus elemen dari DOM setelah animasi selesai
                }
            }, 4000);
        </script>
    @endif

    <nav class="bg-white border-b border-gray-200 shadow-sm p-4 flex justify-between items-center sticky top-0 z-40">
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            Serat Admin Panel
        </h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-red-500 font-semibold hover:text-red-700 hover:bg-red-50 px-4 py-2 rounded-lg transition-colors">Logout</button>
        </form>
    </nav>

    <div class="container mx-auto mt-8 grid grid-cols-1 md:grid-cols-2 gap-8 px-4 max-w-5xl">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <h2 class="text-lg font-bold mb-2 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center">1</span>
                Upload Data Peserta (CSV)
            </h2>
            <hr class="mb-4">
            
            <p class="text-sm text-gray-600 mb-2">Total Peserta Saat Ini: <strong class="text-blue-600 text-lg">{{ $participantsCount }}</strong> orang</p>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-3 mb-5">
                <p class="text-xs text-blue-800"><strong>Format CSV harus 2 kolom tanpa header:</strong> <br> [No WhatsApp], [Nama Lengkap]. <br><span class="text-gray-500 italic">Contoh: 081234567890, Budi Santoso</span></p>
            </div>

            <form action="{{ route('admin.upload-data') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".csv" required class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-blue-700 transition-colors focus:ring-4 focus:ring-blue-300">
                    Proses Data Peserta
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <h2 class="text-lg font-bold mb-2 flex items-center gap-2">
                <span class="bg-emerald-100 text-emerald-700 w-8 h-8 rounded-full flex items-center justify-center">2</span>
                Upload Template Sertifikat
            </h2>
            <hr class="mb-4">

            @if($template)
                <div class="flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 p-3 rounded-lg border border-emerald-200 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <strong>Status:</strong> Template gambar sudah terpasang.
                </div>
            @else
                <div class="flex items-center gap-2 text-sm text-red-700 bg-red-50 p-3 rounded-lg border border-red-200 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <strong>Status:</strong> Template belum ada, PDF akan kosong.
                </div>
            @endif

            <p class="text-xs text-gray-500 mb-4 bg-gray-50 p-3 rounded border">
                Gunakan gambar resolusi lanskap A4 (misal: <strong>3508 x 2480 piksel</strong>) dengan format <strong>JPG</strong> atau <strong>PNG</strong>.
            </p>

            <form action="{{ route('admin.upload-template') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="template" accept="image/png, image/jpeg" required class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-emerald-700 transition-colors focus:ring-4 focus:ring-emerald-300">
                    Upload & Terapkan Template
                </button>
            </form>
        </div>

    </div>
</body>
</html>