<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Serat')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Ikon dari FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex h-screen overflow-hidden">
    
    <!-- SIDEBAR KIRI -->
    <div class="w-64 bg-slate-900 text-white flex flex-col shadow-2xl z-20">
        <!-- Logo -->
        <div class="h-16 flex items-center justify-center border-b border-slate-700 bg-slate-950">
            <h1 class="text-xl font-extrabold text-amber-500 uppercase tracking-widest">
                <i class="fa-solid fa-award mr-2"></i> SERAT
            </h1>
        </div>
        <!-- Menu -->
        <div class="flex-1 overflow-y-auto py-6 px-4">
            <nav class="space-y-2">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-4 px-2">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-all">
                    <i class="fa-solid fa-chart-pie w-6"></i> Dashboard Event
                </a>
                
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6 px-2">Pengaturan</p>
                <!-- Menu Baru: Manajemen User -->
                <a href="#" class="block px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fa-solid fa-users w-6"></i> Manajemen User
                </a>
            </nav>
        </div>
        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-slate-800 text-center text-xs text-slate-500">
            &copy; 2026 Maju Terus
        </div>
    </div>

    <!-- AREA KANAN (Topbar & Konten) -->
    <div class="flex-1 flex flex-col overflow-hidden relative">
        
        <!-- TOPBAR ATAS -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 border-b border-gray-200 z-10">
            <div class="text-gray-800 font-bold text-xl">
                @yield('header', 'Dashboard')
            </div>
            <div class="flex items-center gap-6">
                <!-- Info User -->
                <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span>{{ auth()->user()->name ?? 'Administrator' }}</span>
                </div>
                <!-- Tombol Logout -->
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="text-sm bg-red-50 text-red-600 font-bold px-4 py-2 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors border border-red-100">
                        <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- KONTEN UTAMA YANG BISA DI-SCROLL -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            <!-- Tempat Notifikasi -->
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm font-medium">
                <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <ul class="list-disc pl-5 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Tempat Kotak-kotak (Cards) dimasukkan -->
            @yield('content')
            
        </main>
    </div>
</body>
</html>