<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Serat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Serat Admin Panel</h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-red-600 font-bold hover:underline">Logout</button>
        </form>
    </nav>

    <div class="container mx-auto mt-8 grid grid-cols-2 gap-8 px-4">
        @if(session('success'))
            <div class="col-span-2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-bold mb-4">1. Upload Data Peserta (CSV)</h2>
            <p class="text-sm text-gray-600 mb-4">Total Peserta Saat Ini: <strong>{{ $participantsCount }}</strong> orang</p>
            <p class="text-xs text-gray-500 mb-4">Format CSV harus 2 kolom tanpa header: [NIM], [Nama Lengkap].</p>
            <form action="{{ route('admin.upload-data') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".csv" class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-800">Upload Data</button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-bold mb-4">2. Upload Template Sertifikat</h2>
            @if($template)
                <p class="text-sm text-green-600 mb-4">Status: Template sudah terpasang.</p>
            @else
                <p class="text-sm text-red-600 mb-4">Status: Template belum ada, PDF akan kosong.</p>
            @endif
            <p class="text-xs text-gray-500 mb-4">Gunakan gambar resolusi lanskap A4 (misal: 3508 x 2480 piksel) format JPG/PNG.</p>
            <form action="{{ route('admin.upload-template') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="template" accept="image/png, image/jpeg" class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-800">Upload Template</button>
            </form>
        </div>
    </div>
</body>
</html>