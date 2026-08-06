<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Serat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">
    
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 w-full max-w-md">
        <!-- Logo / Branding -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 mb-4">
                <i class="fa-solid fa-award text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 uppercase tracking-wide">Admin SERAT</h2>
            <p class="text-slate-500 text-sm mt-1">Masuk untuk mengelola event Anda</p>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-600 text-sm px-4 py-3 rounded-lg flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-slate-400"></i>
                    </div>
                    <input type="email" name="email" class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow bg-slate-50 focus:bg-white" placeholder="admin@serat.com" required>
                </div>
            </div>
            
            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-slate-400"></i>
                    </div>
                    <input type="password" name="password" class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow bg-slate-50 focus:bg-white" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 mt-2">
                Masuk ke Dashboard
            </button>
        </form>
        
        <div class="mt-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Sistem Registrasi Acara Terpadu
        </div>
    </div>

</body>
</html>