<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $formTitle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @if(isset($eventBackground) && $eventBackground)
    <style>
        body {
            background-image: url('{{ asset($eventBackground) }}') !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            background-repeat: no-repeat !important;
        }
    </style>
    @endif
</head>
<body class="bg-slate-100 text-slate-900 font-sans antialiased min-h-screen flex flex-col justify-between relative">
    
    <!-- Overlay gelap jika ada background gambar agar form lebih menonjol -->
    @if(isset($eventBackground) && $eventBackground)
    <div class="absolute inset-0 bg-slate-900/30 z-0"></div>
    @endif

    <div class="relative z-10 py-10 px-4 sm:px-6 lg:px-8 max-w-xl mx-auto w-full flex-grow flex flex-col justify-center">
        
        <!-- Efek Glassmorphism pada Card Utama -->
        <div class="bg-white/95 backdrop-blur-md p-8 sm:p-10 shadow-2xl rounded-2xl border border-white/50 relative overflow-hidden">
            
            <!-- Aksen Garis Atas -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-purple-600"></div>

            <div class="text-center mb-8 mt-2">
                <h1 class="text-2xl font-extrabold text-slate-800 leading-tight">{{ $formTitle }}</h1>
                <p class="text-slate-500 text-sm mt-2">Silakan lengkapi data diri Anda di bawah ini.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-start gap-3">
                    <i class="fa-solid fa-circle-check mt-0.5 text-lg"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if($errors->any() || session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-lg"></i>
                    <div>
                        @if(session('error')) <p class="font-semibold">{{ session('error') }}</p> @endif
                        @if($errors->any())
                            <ul class="list-disc pl-4 mt-1 space-y-1 font-medium">
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endif

            <!-- FORM PENDAFTARAN -->
            <form action="{{ route('guest.register.submit', ['slug' => $slug]) }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-slate-50/50 border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors" placeholder="Masukkan nama lengkap" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-50/50 border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors" placeholder="contoh@email.com" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="tel" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            class="w-full bg-slate-50/50 border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors" 
                            placeholder="Contoh: 081122223333" 
                            pattern="^08[0-9]{7,13}$" 
                            title="Nomor WhatsApp harus dimulai dengan angka 08 dan hanya berisi angka (Contoh: 081122223333)"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            required>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5"><i class="fa-solid fa-circle-info mr-1 text-indigo-400"></i> Nomor ini dibutuhkan untuk mengunduh sertifikat nanti.</p>
                </div>

                <!-- Field Dinamis Buatan Admin -->
                @foreach($fields as $field)
                    <div class="pt-2 border-t border-slate-100">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ $field->label }} @if($field->is_required) <span class="text-rose-500">*</span> @endif
                        </label>
                        @if($field->type === 'textarea')
                            <textarea name="dynamic_{{ $field->id }}" rows="3" class="w-full bg-slate-50/50 border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors" placeholder="Tuliskan di sini..." {{ $field->is_required ? 'required' : '' }}>{{ old('dynamic_' . $field->id) }}</textarea>
                        @else
                            <input type="{{ $field->type === 'date' ? 'date' : ($field->type === 'number' ? 'number' : 'text') }}" name="dynamic_{{ $field->id }}" value="{{ old('dynamic_' . $field->id) }}" class="w-full bg-slate-50/50 border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors" {{ $field->is_required ? 'required' : '' }}>
                        @endif
                    </div>
                @endforeach

                <div class="pt-6">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>

            @if(isset($eventBackground) && $eventBackground)
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="{{ url('/download-background') }}" class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                    <i class="fa-solid fa-download"></i> Download Background Acara
                </a>
            </div>
            @endif

        </div>
    </div>

    <footer class="relative z-10 text-center py-4 text-xs font-medium text-slate-600 bg-white/80 backdrop-blur-sm mt-auto shadow-[0_-2px_15px_rgba(0,0,0,0.05)] border-t border-slate-200">
        &copy; {{ date('Y') }} Sistem Registrasi Acara Terpadu (SERAT).
    </footer>
</body>
</html>