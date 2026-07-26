<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal E-Sertifikat - {{ $seminarTitle ?? 'Acara' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #121212;
            background-image: 
                radial-gradient(circle at top right, rgba(212, 175, 55, 0.15), transparent 40%),
                radial-gradient(circle at bottom left, rgba(212, 175, 55, 0.1), transparent 40%);
            color: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .gold-gradient-text {
            background: linear-gradient(to right, #d4af37, #fcf6ba, #d4af37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gold-border {
            border: 1px solid #d4af37;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.15);
        }
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
                Selamat Datang di Portal E-Sertifikat | {{ $seminarTitle ?? 'SCAR 2026' }} | Silakan Masukkan No. WhatsApp Anda untuk Mengunduh Sertifikat
            </marquee>
        </div>

        <div class="bg-[#1a1a1a] p-8 rounded-xl gold-border w-full relative overflow-hidden">
            
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo1.png') }}" alt="Logo Acara" class="w-24 h-24 object-cover rounded-full border-2 border-[#d4af37] shadow-[0_0_15px_rgba(212,175,55,0.4)]">
            </div>        
            
            <div class="text-center mb-6">
                <h2 class="text-3xl font-extrabold gold-gradient-text tracking-wide">PORTAL SERTIFIKAT</h2>
                <!-- Sub Judul Dinamis -->
                <p class="text-gray-400 mt-2 text-xs uppercase tracking-[0.3em]">{{ $seminarTitle ?? 'SCAR 2026' }}</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-900/30 border border-red-500/50 text-red-300 text-center text-sm font-bold shadow-lg tracking-wide">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @if(isset($isOpen) && $isOpen)
                <form action="{{ route('claim.process') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-gray-300 text-sm font-bold mb-2 uppercase tracking-wide text-center">Masukkan No. WhatsApp Anda</label>
                        <input type="text" id="nimInput" name="nim" required autocomplete="off" class="w-full py-4 px-4 bg-[#2a2a2a] border border-gray-600 rounded text-[#fcf6ba] text-center text-xl font-bold focus:outline-none focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] transition-all placeholder-gray-500 tracking-widest" placeholder="Contoh: 081234567890">
                    </div>

                    <div id="resultBox" class="hidden mb-6 p-5 bg-[#222222] border border-[#d4af37] rounded-lg text-center transform transition-all shadow-[0_0_15px_rgba(212,175,55,0.2)]">
                        <p class="text-sm text-gray-400 mb-1">Sertifikat atas nama:</p>
                        <p id="nameOutput" class="text-2xl font-bold text-[#fcf6ba] mb-5 uppercase tracking-wide"></p>
                        
                        <button type="submit" class="w-full text-center gold-btn font-extrabold py-4 px-4 rounded-lg transition-all transform hover:scale-105 uppercase tracking-wider text-sm">
                            <i class="fa-solid fa-cloud-arrow-down mr-2"></i> Unduh File PDF
                        </button>
                    </div>

                    <p id="errorMsg" class="hidden text-red-400 text-sm text-center bg-red-900 bg-opacity-20 p-3 rounded border border-red-800/50"></p>
                </form>

                <script>
                    const nimInput = document.getElementById('nimInput');
                    const resultBox = document.getElementById('resultBox');
                    const nameOutput = document.getElementById('nameOutput');
                    const errorMsg = document.getElementById('errorMsg');

                    nimInput.addEventListener('input', function() {
                        let nim = this.value.trim();
                        if(nim.length >= 5) {
                            fetch(`/api/check-nim/${nim}`)
                                .then(response => response.json())
                                .then(data => {
                                    if(data.status === 'success') {
                                        resultBox.classList.remove('hidden');
                                        errorMsg.classList.add('hidden');
                                        nameOutput.textContent = data.name;
                                    } else {
                                        resultBox.classList.add('hidden');
                                        errorMsg.classList.remove('hidden');
                                        errorMsg.textContent = "Nomor WhatsApp tidak ditemukan di database kami.";
                                    }
                                });
                        } else {
                            resultBox.classList.add('hidden');
                            errorMsg.classList.add('hidden');
                        }
                    });
                </script>
            @else
                <div class="p-6 bg-[#222222] border border-gray-700 rounded-lg text-center transform transition-all">
                    <i class="fa-solid fa-lock text-5xl text-gray-500 mb-4"></i>
                    <h3 class="text-lg font-bold text-[#fcf6ba] mb-2 uppercase tracking-widest">Portal Tertutup</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Sesi unduhan sertifikat sedang tidak aktif. Harap menunggu instruksi dari panitia.</p>
                </div>
            @endif

        </div>
    </div>
</body>
</html>