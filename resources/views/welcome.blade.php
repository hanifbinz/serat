<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal E-Sertifikat - SCAG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Desain Background Gelap dengan Gradasi Emas Tipis di Sudut */
        body {
            background-color: #121212;
            background-image: 
                radial-gradient(circle at top right, rgba(212, 175, 55, 0.15), transparent 40%),
                radial-gradient(circle at bottom left, rgba(212, 175, 55, 0.1), transparent 40%);
            color: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Teks dengan Warna Gradasi Emas */
        .gold-gradient-text {
            background: linear-gradient(to right, #d4af37, #fcf6ba, #d4af37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Border Emas Bersinar untuk Kotak Tengah */
        .gold-border {
            border: 1px solid #d4af37;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.15);
        }

        /* Tombol Unduh Emas */
        .gold-btn {
            background: linear-gradient(to right, #d4af37, #b38728);
            color: #121212;
        }
        .gold-btn:hover {
            background: linear-gradient(to right, #fcf6ba, #d4af37);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen p-4">
    
    <div class="w-full max-w-md flex flex-col">
        
        <div class="w-full bg-gradient-to-r from-[#b38728] via-[#d4af37] to-[#b38728] text-black font-bold py-2 mb-4 rounded-md border border-[#fcf6ba] shadow-[0_0_15px_rgba(212,175,55,0.2)]">
            <marquee scrollamount="6" behavior="scroll" direction="left" class="text-sm uppercase tracking-widest px-4">
                Selamat Datang di Portal E-Sertifikat | SCAR (Supply Chain Agile & Resilient) | Silakan Masukkan No. WhatsApp Anda untuk Mengunduh Sertifikat
            </marquee>
        </div>

        <div class="bg-[#1a1a1a] p-8 rounded-xl gold-border w-full relative overflow-hidden">
            
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo1.png') }}" 
                     alt="Logo SCAG" 
                     class="w-24 h-24 object-cover rounded-full border-2 border-[#d4af37] shadow-[0_0_15px_rgba(212,175,55,0.4)]">
            </div>        
            
            <div class="text-center mb-6">
                <h2 class="text-3xl font-extrabold gold-gradient-text tracking-wide">PORTAL SERTIFIKAT</h2>
                <p class="text-gray-400 mt-2 text-xs uppercase tracking-[0.3em]">SCAR 2026</p>
            </div>

            <!-- Tempat Notifikasi Error / Success -->
            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-900/30 border border-red-500/50 text-red-300 text-center text-sm font-bold shadow-lg tracking-wide">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-900/30 border border-green-500/50 text-green-300 text-center text-sm font-bold shadow-lg tracking-wide">
                    ✅ {{ session('success') }}
                </div>
            @endif


            <!-- LOGIKA LINK DINAMIS: TAMPILKAN FORM ATAU GEMBOK -->
            @if(isset($code) && $code != null)
                <!-- Tampilan Jika Link Valid -->
                <form action="{{ url('/claim/' . $code) }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-gray-300 text-sm font-bold mb-2 uppercase tracking-wide text-center">Masukkan No. WhatsApp Anda</label>
                        <input type="text" name="nim" required class="w-full py-4 px-4 bg-[#2a2a2a] border border-gray-600 rounded text-[#fcf6ba] text-center text-xl font-bold focus:outline-none focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] transition-all placeholder-gray-500 tracking-widest" placeholder="Contoh: 081234567890">
                    </div>

                    <button type="submit" class="w-full text-center gold-btn font-extrabold py-4 px-4 rounded-lg transition-all transform hover:scale-105 uppercase tracking-wider text-sm">
                        <i class="fa-solid fa-cloud-arrow-down mr-2"></i> Verifikasi & Unduh
                    </button>
                </form>
            @else
                <!-- Tampilan Jika Buka Link Tanpa Code (Nyasar / Sesi Ditutup) -->
                <div class="p-6 bg-[#222222] border border-gray-700 rounded-lg text-center transform transition-all">
                    <i class="fa-solid fa-lock text-5xl text-gray-500 mb-4"></i>
                    <h3 class="text-lg font-bold text-[#fcf6ba] mb-2 uppercase tracking-widest">Portal Tertutup</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Sesi unduhan tidak aktif. Gunakan <strong>Link Resmi</strong> yang diberikan oleh panitia.</p>
                </div>
            @endif

        </div>

    </div>

</body>
</html>