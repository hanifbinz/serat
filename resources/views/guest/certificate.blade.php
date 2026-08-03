<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Download Sertifikat - {{ $eventName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased min-h-screen flex flex-col justify-between">
    
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-xl mx-auto w-full">
        
        <div class="bg-white p-8 shadow-md rounded-lg border text-center">
            <h1 class="text-2xl font-bold mb-2 text-blue-600">Portal Sertifikat</h1>
            <p class="text-gray-600 mb-6 font-medium">{{ $eventName }}</p>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm text-left">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm text-left">
                    {{ session('error') }}
                </div>
            @endif

            @if($isOpen == '1')
                <p class="text-sm text-gray-600 mb-4 text-left">Silakan masukkan Nomor WhatsApp yang Anda gunakan saat mendaftar untuk mengunduh sertifikat.</p>
                
                <form action="{{ route('guest.certificate.download') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                        <input type="text" name="phone" placeholder="Contoh: 08123456789" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">
                            Cari & Download Sertifikat
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-6 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="font-semibold text-lg">Portal Ditutup</p>
                    <p class="text-sm mt-1">Mohon maaf, akses download sertifikat untuk acara ini sedang ditutup oleh panitia.</p>
                </div>
            @endif
        </div>

    </div>

    <footer class="text-center py-6 text-xs text-gray-500">
        &copy; {{ date('Y') }} Sistem Manajemen Acara. All rights reserved.
    </footer>
</body>
</html>