<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-In Kehadiran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full border-t-4 border-blue-500">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-extrabold text-gray-800">Presensi Kehadiran</h2>
            <p class="text-sm text-gray-500 mt-2">Masukkan No. WhatsApp terdaftar Anda untuk mengaktifkan e-Sertifikat.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkin', $code) }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp</label>
                <input type="text" name="nim" placeholder="Contoh: 081234567890" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                Kirim Bukti Kehadiran
            </button>
        </form>
    </div>
</body>
</html>