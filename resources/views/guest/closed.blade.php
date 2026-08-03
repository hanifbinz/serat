<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Ditutup</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased min-h-screen flex items-center justify-center">
    
    <div class="px-4 max-w-md w-full">
        <div class="bg-white p-8 shadow-md rounded-lg border text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Pendaftaran Ditutup</h1>
            <p class="text-gray-600 mb-6">{{ $message ?? 'Mohon maaf, pendaftaran saat ini sedang ditutup.' }}</p>
            
            <a href="{{ route('guest.certificate.search') }}" class="text-blue-600 hover:text-blue-800 underline text-sm">
                Ke Portal Sertifikat
            </a>
        </div>
    </div>

</body>
</html>