<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengelola - PawPalace Petshop Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex items-center justify-center p-4 antialiased font-sans">

    <div class="w-full max-w-md space-y-6">
        
        <!-- Logo & Title -->
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center mx-auto text-2xl shadow-xl shadow-amber-500/20">
                🐾
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">PawPalace Admin</h1>
            <p class="text-xs text-slate-400">Portal Terproteksi Pengelola Toko & Logistik</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-800 border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl">
            
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-xs rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Email Administrator</label>
                    <input type="email" name="email" value="{{ old('email', 'admin@petshop.test') }}" required autofocus
                           placeholder="admin@petshop.test"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-900 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500">
                    @error('email') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Kata Sandi (Password)</label>
                    <input type="password" name="password" required value="password123"
                           placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-900 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500">
                    @error('password') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-amber-500 focus:ring-amber-500">
                        <span>Ingat Sesi Saya</span>
                    </label>
                    <span class="text-amber-400 font-mono text-[11px]">Default: password123</span>
                </div>

                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-slate-950 font-extrabold text-sm shadow-lg shadow-amber-500/20 transition transform active:scale-95">
                    Masuk ke Dashboard &rarr;
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-700/60 text-center">
                <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-amber-400 transition">
                    &larr; Kembali ke Beranda Toko (Storefront)
                </a>
            </div>

        </div>

    </div>

</body>
</html>
