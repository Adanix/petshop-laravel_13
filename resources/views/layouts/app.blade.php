<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PawPalace Petshop - Belanja Kebutuhan Anabul Cepat & Terpercaya')</title>
    <meta name="description" content="Toko kebutuhan hewan peliharaan modern. Checkout instan tanpa ribet bikin akun!">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-full flex flex-col text-slate-800 antialiased font-sans">

    <!-- Top Notification Bar -->
    <div class="bg-gradient-to-r from-amber-600 via-orange-500 to-amber-600 text-white text-xs font-medium py-1.5 px-4 text-center tracking-wide">
        <span>🐾 <strong>Belanja Tanpa Ribet:</strong> Zero-Login Checkout! Pilih barang, isi alamat, kurir langsung kirim ke rumah Anda.</span>
    </div>

    <!-- Main Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-4">
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-400 flex items-center justify-center text-white shadow-md shadow-amber-500/20 group-hover:scale-105 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 100-18 9 9 0 000 18zM8.25 9.75h.008v.008H8.25V9.75zm7.5 0h.008v.008h-.008V9.75zm-6 4.5a3.75 3.75 0 005.25 0" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">PawPalace</span>
                        <span class="hidden sm:inline-block text-xs font-semibold uppercase tracking-wider text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded ml-1">Petshop</span>
                    </div>
                </a>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-lg mx-4">
                    <form action="{{ route('home') }}" method="GET" class="w-full relative">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari makanan kucing, vitamin, pasir gumpal..." 
                               class="w-full pl-10 pr-4 py-2 text-sm bg-slate-100 hover:bg-slate-50 focus:bg-white border border-transparent focus:border-amber-400 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </form>
                </div>

                <!-- Navigation Actions -->
                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <!-- Lacak Pesanan Link -->
                    <a href="{{ route('orders.track-form') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-slate-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Lacak Pesanan</span>
                    </a>

                    <!-- Cart Button with Live Counter Badge -->
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-2 px-3 sm:px-4 py-2 text-sm font-semibold text-slate-800 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded-full border border-amber-200 transition">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="hidden sm:inline">Keranjang</span>
                        <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-amber-600 rounded-full">
                            {{ $globalCartCount ?? 0 }}
                        </span>
                    </a>

                    <div class="h-6 w-px bg-slate-200"></div>

                    <!-- Admin Portal Link -->
                    <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 hover:text-slate-800 transition" title="Portal Pengelola">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="hidden md:inline">Admin</span>
                    </a>

                </div>
            </div>

            <!-- Mobile Search Bar -->
            <div class="md:hidden pb-3">
                <form action="{{ route('home') }}" method="GET" class="relative">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari makanan kucing, vitamin..." 
                           class="w-full pl-9 pr-4 py-2 text-xs bg-slate-100 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="mb-4 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm shadow-xs">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1 font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm shadow-xs">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1 font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 flex items-center gap-3 p-4 bg-sky-50 border border-sky-200 text-sky-800 rounded-xl text-sm shadow-xs">
                <svg class="w-5 h-5 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1 font-medium">{{ session('info') }}</div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center text-white">🐾</div>
                        <span class="text-lg font-bold text-slate-900">PawPalace Petshop</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed mb-4">
                        Pusat perbelanjaan online kebutuhan kucing & anjing terpercaya. Praktis tanpa login, pengiriman cepat & amanah.
                    </p>
                    <div class="text-xs text-slate-400">
                        © {{ date('Y') }} PawPalace Petshop. All rights reserved.
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-wider mb-3">Kategori Pilihan</h3>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li><a href="{{ route('home', ['category' => 'makanan-kucing']) }}" class="hover:text-amber-600 transition">Makanan Kucing</a></li>
                        <li><a href="{{ route('home', ['category' => 'makanan-anjing']) }}" class="hover:text-amber-600 transition">Makanan Anjing</a></li>
                        <li><a href="{{ route('home', ['category' => 'pasir-kebersihan']) }}" class="hover:text-amber-600 transition">Pasir & Kebersihan</a></li>
                        <li><a href="{{ route('home', ['category' => 'vitamin-perawatan']) }}" class="hover:text-amber-600 transition">Vitamin & Perawatan</a></li>
                        <li><a href="{{ route('home', ['category' => 'aksesoris-mainan']) }}" class="hover:text-amber-600 transition">Aksesoris & Mainan</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-wider mb-3">Layanan Pengiriman</h3>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="px-2.5 py-1 bg-slate-100 rounded text-slate-700 font-semibold border border-slate-200">JNE Express</span>
                        <span class="px-2.5 py-1 bg-slate-100 rounded text-slate-700 font-semibold border border-slate-200">J&T Express</span>
                        <span class="px-2.5 py-1 bg-slate-100 rounded text-slate-700 font-semibold border border-slate-200">SiCepat</span>
                        <span class="px-2.5 py-1 bg-amber-100 rounded text-amber-800 font-semibold border border-amber-200">Kurir Instan SameDay</span>
                    </div>
                    <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-wider mt-5 mb-2">Bantuan & Lacak</h3>
                    <a href="{{ route('orders.track-form') }}" class="text-sm text-amber-600 font-medium hover:underline inline-flex items-center gap-1">
                        Cek Status Pesanan Anda &rarr;
                    </a>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-wider mb-3">Keamanan Transaksi</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-3">
                        Pemesanan terlindungi dengan arsitektur Guest-Checkout transparan & token invoice unik tanpa perlu membagikan data akun.
                    </p>
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/60 text-xs text-amber-900">
                        <strong>WhatsApp Hotline:</strong> 0812-3456-7890<br>
                        Senin - Minggu: 08.00 - 21.00 WIB
                    </div>
                </div>

            </div>
        </div>
    </footer>

</body>
</html>
