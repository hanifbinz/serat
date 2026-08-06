<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - {{ $eventName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .marquee-container { overflow: hidden; white-space: nowrap; background: #1e3a8a; color: white; padding: 8px 0; font-size: 0.875rem; }
        .marquee-text { display: inline-block; padding-left: 100%; animation: marquee 15s linear infinite; }
        @keyframes marquee { 0% { transform: translate(0, 0); } 100% { transform: translate(-100%, 0); } }
        
        .loader { border: 3px solid #f3f3f3; border-top: 3px solid #3498db; border-radius: 50%; width: 20px; height: 20px; animation: spin 1s linear infinite; display: inline-block; vertical-align: middle; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center">

    <!-- Marquee Text Berjalan -->
    @if(isset($marqueeText) && $marqueeText != '')
    <div class="marquee-container w-full shadow-md">
        <span class="marquee-text"><i class="fa-solid fa-bullhorn mr-2"></i> {{ $marqueeText }}</span>
    </div>
    @endif

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100 mt-10 mb-10">
        
        <!-- Header & Dynamic Logo -->
        <div class="bg-blue-600 p-8 text-center flex flex-col items-center">
        @php
            $rawLogoPath = \App\Models\Setting::getValue('event_logo');
            $cleanLogoPath = $rawLogoPath ? str_replace('public/', '', $rawLogoPath) : null;
        @endphp

        @if($cleanLogoPath)
            <!-- Tampilkan Logo Background Putih Melingkar -->
            <img src="{{ asset('storage/' . $cleanLogoPath) }}" alt="Logo Acara" class="mx-auto object-contain bg-white rounded-full p-2 mb-3 shadow-sm" style="height: 90px; width: 90px;">
        @endif
            <h1 class="text-2xl font-bold text-white mb-1">E-Sertifikat</h1>
            <p class="text-blue-100 text-sm font-medium">{{ $eventName }}</p>
        </div>

        <div class="p-8">
            
            <!-- Notifikasi Jika Server / PDF Error (Misal belum ada template) -->
            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 flex items-start border border-red-100 shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5 mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($isOpen == '0')
                <div class="text-center py-6">
                    <div class="inline-block bg-red-100 text-red-500 p-4 rounded-full mb-4">
                        <i class="fa-solid fa-lock text-3xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Portal Ditutup</h2>
                    <p class="text-slate-500 text-sm mt-2">Maaf, portal unduh sertifikat untuk acara ini ditutup oleh panitia.</p>
                </div>
            @else
                <!-- Area Notifikasi Error AJAX -->
                <div id="errorAlert" class="hidden bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 flex items-start border border-red-100 shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5 mr-2"></i>
                    <span id="errorMessage">Error</span>
                </div>

                <!-- Form Pencarian WA -->
                <div id="searchSection">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor WhatsApp Terdaftar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-brands fa-whatsapp text-slate-400"></i>
                            </div>
                            <input type="text" id="waNumber" placeholder="Contoh: 081234567890" class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required autocomplete="off">
                        </div>
                    </div>
                    <button onclick="checkCertificate()" id="btnSearch" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition-all flex justify-center items-center">
                        <span id="btnText"><i class="fa-solid fa-magnifying-glass mr-2"></i> Cek Sertifikat</span>
                        <div id="btnLoading" class="loader hidden ml-2"></div>
                    </button>
                </div>

                <!-- Area Hasil Pencarian (Sederhana) -->
                <div id="resultSection" class="hidden text-center fade-in">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-6 shadow-sm">
                        <div class="text-green-500 mb-2"><i class="fa-solid fa-circle-check text-4xl"></i></div>
                        <h3 class="text-slate-800 font-bold text-lg">Sertifikat Ditemukan!</h3>
                        <p class="text-slate-600 text-sm mt-1">Atas Nama:</p>
                        <p id="participantName" class="text-blue-700 font-bold text-xl mt-1"></p>
                    </div>

                    <!-- Tombol Download Langsung -->
                    <a id="downloadBtn" href="#" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all mb-4">
                        <i class="fa-solid fa-cloud-arrow-down mr-2"></i> Unduh PDF Sekarang
                    </a>

                    <!-- Tombol Kembali -->
                    <div class="mt-4">
                        <button onclick="resetForm()" class="text-sm text-slate-500 hover:text-blue-600 font-medium underline">
                            Cari Nomor Lain
                        </button>
                    </div>
                </div>

            @endif
        </div>
    </div>

    <!-- Script AJAX Vanilla JS -->
    <script>
        // Trigger fungsi checkCertificate jika tombol Enter ditekan pada input WA
        document.getElementById("waNumber").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                checkCertificate();
            }
        });

        function checkCertificate() {
            let phone = document.getElementById('waNumber').value;
            let errorAlert = document.getElementById('errorAlert');
            let errorMessage = document.getElementById('errorMessage');
            let btnText = document.getElementById('btnText');
            let btnLoading = document.getElementById('btnLoading');

            if (!phone) {
                errorMessage.innerText = "Nomor WhatsApp wajib diisi.";
                errorAlert.classList.remove('hidden');
                return;
            }

            errorAlert.classList.add('hidden');
            btnText.innerText = "Mencari...";
            btnLoading.classList.remove('hidden');
            document.getElementById('btnSearch').disabled = true;

            fetch('{{ route("guest.certificate.check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ phone: phone })
            })
            .then(response => response.json())
            .then(data => {
                btnText.innerHTML = '<i class="fa-solid fa-magnifying-glass mr-2"></i> Cek Sertifikat';
                btnLoading.classList.add('hidden');
                document.getElementById('btnSearch').disabled = false;

                if (data.status === 'success') {
                    // Sembunyikan pencarian, tampilkan hasil
                    document.getElementById('searchSection').classList.add('hidden');
                    document.getElementById('resultSection').classList.remove('hidden');
                    
                    // Set nama dan link download
                    document.getElementById('participantName').innerText = data.name;
                    document.getElementById('downloadBtn').href = data.link;
                } else {
                    errorMessage.innerText = data.message;
                    errorAlert.classList.remove('hidden');
                }
            })
            .catch(error => {
                btnText.innerHTML = '<i class="fa-solid fa-magnifying-glass mr-2"></i> Cek Sertifikat';
                btnLoading.classList.add('hidden');
                document.getElementById('btnSearch').disabled = false;
                errorMessage.innerText = "Terjadi kesalahan server. Pastikan rute dapat diakses.";
                errorAlert.classList.remove('hidden');
            });
        }

        function resetForm() {
            document.getElementById('resultSection').classList.add('hidden');
            document.getElementById('searchSection').classList.remove('hidden');
            document.getElementById('waNumber').value = '';
        }
    </script>
</body>
</html>