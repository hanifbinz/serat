<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi Mandiri - {{ $seminarTitle ?? 'Acara' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background-color: #121212; 
            background-image: radial-gradient(circle at top right, rgba(212, 175, 55, 0.15), transparent 40%), radial-gradient(circle at bottom left, rgba(212, 175, 55, 0.1), transparent 40%); 
            color: #f3f4f6; 
        }
        .gold-border { 
            border: 1px solid #d4af37; 
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.15); 
        }
        .gold-btn { 
            background: linear-gradient(to right, #d4af37, #b38728); 
            color: #121212; 
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        
        <div class="bg-[#1a1a1a] p-8 rounded-xl gold-border w-full relative overflow-hidden">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-extrabold text-[#d4af37] tracking-wide">FORM REGISTRASI</h2>
                <p class="text-gray-400 mt-1 text-xs uppercase tracking-[0.2em]">{{ $seminarTitle ?? 'SCAR 2026' }}</p>
            </div>

            @if(session('success'))
                <div class="mb-5 p-4 rounded bg-green-900/40 border border-green-500/50 text-green-300 text-center text-sm font-bold">
                    <i class="fa-solid fa-circle-check text-xl block mb-1"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="mb-5 p-3 rounded bg-red-900/40 border border-red-500/50 text-red-300 text-center text-sm font-medium">
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            @if(isset($isRegOpen) && $isRegOpen)
                <form action="{{ route('register.process') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-300 text-xs font-bold mb-2 uppercase tracking-wide">Nomor WhatsApp Aktif</label>
                        <input type="text" name="nim" required class="w-full py-3 px-4 bg-[#2a2a2a] border border-gray-600 rounded text-[#fcf6ba] text-base font-bold outline-none focus:border-[#d4af37]" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-300 text-xs font-bold mb-2 uppercase tracking-wide">Nama Lengkap + Gelar</label>
                        <input type="text" name="name" required class="w-full py-3 px-4 bg-[#2a2a2a] border border-gray-600 rounded text-[#fcf6ba] text-base font-bold outline-none focus:border-[#d4af37]" placeholder="Contoh: Budi Santoso, S.Kom">
                    </div>
                    <button type="submit" class="w-full gold-btn font-extrabold py-3.5 rounded-lg uppercase tracking-wider text-sm shadow-lg hover:scale-[1.02] transition-transform">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pendaftaran
                    </button>
                </form>
            @else
                <div class="p-6 bg-[#222222] border border-gray-700 rounded-lg text-center">
                    <i class="fa-solid fa-door-closed text-4xl text-gray-500 mb-3"></i>
                    <h3 class="text-md font-bold text-[#fcf6ba] uppercase tracking-widest">Pendaftaran Ditutup</h3>
                    <p class="text-xs text-gray-400 mt-2">Form pendaftaran sedang tidak menerima tanggapan baru. Silakan hubungi panitia.</p>
                </div>
            @endif
        </div>

    </div>
</body>
</html>