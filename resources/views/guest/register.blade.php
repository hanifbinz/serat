<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $formTitle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased min-h-screen flex flex-col justify-between">
    
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-xl mx-auto w-full">
        
        <div class="bg-white p-8 shadow-md rounded-lg border">
            <h1 class="text-2xl font-bold text-center mb-6 text-blue-600">{{ $formTitle }}</h1>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('guest.register.submit') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Field Utama: Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <!-- Field Utama: WhatsApp -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <!-- Field Dinamis Buatan Admin -->
                @foreach($fields as $field)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $field->label }} <span class="text-red-500">*</span>
                        </label>
                        <input type="{{ $field->type }}" name="dynamic_{{ $field->id }}" value="{{ old('dynamic_' . $field->id) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                @endforeach

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>

            @if($eventBackground)
                <div class="mt-6 border-t pt-4 text-center">
                    <p class="text-xs text-gray-500 mb-2">Butuh background acara resmi?</p>
                    <a href="{{ route('guest.download.background') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold py-2 px-4 rounded transition">
                        Download Background Acara
                    </a>
                </div>
            @endif
        </div>

    </div>

    <footer class="text-center py-6 text-xs text-gray-500">
        &copy; {{ date('Y') }} Sistem Manajemen Acara. All rights reserved.
    </footer>
</body>
</html>