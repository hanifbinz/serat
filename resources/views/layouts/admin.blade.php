<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Serat')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans leading-normal tracking-normal flex h-screen overflow-hidden text-slate-800">
    
    <!-- OVERLAY UNTUK MOBILE -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-20 hidden lg:hidden backdrop-blur-sm transition-opacity" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR KIRI (VERSI PUTIH / LIGHT THEME) -->
    <div id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:relative lg:translate-x-0 z-30 w-72 bg-white border-r border-slate-200 flex flex-col shadow-2xl lg:shadow-none transition-all duration-300 ease-in-out">
        <!-- Logo -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 bg-white">
            <h1 class="text-xl font-extrabold text-indigo-600 uppercase tracking-widest flex items-center gap-2">
                <i class="fa-solid fa-award text-2xl"></i> SERAT
            </h1>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <!-- Menu -->
        <div class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
            <nav class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3 mt-4 px-3">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium' }} transition-all">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i> Dashboard
                </a>

                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3 mt-8 px-3">Kelola Event</p>
                <a href="{{ route('admin.participants.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.participants.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium' }} transition-all">
                    <i class="fa-solid fa-users-gear w-5 text-center"></i> Data Peserta
                </a>
                
                <a href="{{ route('admin.registration-settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.registration-settings.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium' }} transition-all">
                    <i class="fa-solid fa-sliders w-5 text-center"></i> Registrasi
                </a>

                <a href="{{ route('admin.certificate.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.certificate.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium' }} transition-all">
                    <i class="fa-solid fa-certificate w-5 text-center"></i> Sertifikat
                </a>
                
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3 mt-8 px-3">Pengaturan System</p>
                @can('is-administrator')
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium' }} transition-all">
                    <i class="fa-solid fa-users w-5 text-center"></i> Manajemen User
                </a>
                @endcan
            </nav>
        </div>
        
        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-[10px] text-slate-500 font-medium truncate">Admin Panel</p>
            </div>
        </div>
    </div>

    <!-- AREA KANAN (Topbar & Konten) -->
    <div class="flex-1 flex flex-col overflow-hidden relative w-full">
        
        <!-- TOPBAR ATAS -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 lg:px-8 border-b border-slate-200 z-10">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="text-slate-500 hover:text-indigo-600 focus:outline-none p-2 rounded-lg hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h2 class="text-slate-800 font-bold text-lg hidden sm:block">
                    @yield('header', 'Dashboard')
                </h2>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Tombol Logout -->
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="text-sm bg-rose-50 text-rose-600 font-semibold px-4 py-2 rounded-lg hover:bg-rose-100 transition-colors border border-rose-100 flex items-center gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i> <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- KONTEN UTAMA -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Alert Area -->
                @if(session('success'))
                <div class="alert-box bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="alert-box bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm">
                    <ul class="list-disc pl-5 font-medium text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            if (window.innerWidth >= 1024) {
                // LOGIKA DESKTOP: Geser sidebar ke kiri sejauh lebarnya sendiri (-ml-72)
                sidebar.classList.toggle('-ml-72');
            } else {
                // LOGIKA MOBILE: Mainkan posisi translate dan tampilkan overlay gelap
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        }

        // Auto-hide alert
        document.addEventListener('DOMContentLoaded', function() {
            let alerts = document.querySelectorAll('.alert-box');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = "all 0.5s ease";
                    alert.style.opacity = "0";
                    alert.style.transform = "translateY(-10px)";
                    setTimeout(() => alert.remove(), 500);
                }, 4000);
            });
        });
    </script>
</body>
</html>