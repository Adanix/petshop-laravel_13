@extends('layouts.app')

@section('title', 'PawPalace Petshop - Katalog Kebutuhan Anabul')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

    <!-- Hero Promotional Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-600 via-orange-500 to-amber-700 text-white shadow-xl shadow-amber-500/10">
        <div class="absolute -right-10 -bottom-10 w-80 h-80 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-72 h-72 bg-amber-400/20 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 px-6 py-10 sm:px-12 sm:py-16 max-w-2xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-md text-amber-100 mb-4 border border-white/20">
                ✨ Guest-First Shopping • Tanpa Akun & Password
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight text-white mb-4">
                Nutrisi & Perlengkapan Terbaik untuk Anabul
            </h1>
            <p class="text-sm sm:text-base text-amber-100 mb-6 leading-relaxed">
                Pilih makanan sehat, pasir gumpal wangi, suplemen vitamin, dan aksesoris berkualitas. Langsung bayar dan lacak kurir tanpa hambatan login!
            </p>
            <div class="flex flex-wrap items-center gap-3">
                <a href="#katalog" class="px-6 py-3 rounded-full bg-white text-amber-700 font-bold text-sm shadow-md hover:bg-amber-50 transition transform hover:-translate-y-0.5">
                    Mulai Belanja Sekarang ↓
                </a>
                <a href="{{ route('orders.track-form') }}" class="px-5 py-3 rounded-full bg-amber-900/40 hover:bg-amber-900/60 border border-white/30 text-white font-semibold text-sm transition">
                    🔍 Cek Resi / Lacak Pesanan
                </a>
            </div>
        </div>
    </div>

    <!-- Category Filter Bar & Search -->
    <div id="katalog" class="space-y-4 pt-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-5">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Katalog Produk</h2>
                <p class="text-xs sm:text-sm text-slate-500">
                    Menampilkan produk pilihan berkualitas dengan kalkulasi berat akurat untuk pengiriman.
                </p>
            </div>

            <!-- Sorting Dropdown -->
            <form action="{{ route('home') }}" method="GET" class="flex items-center gap-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <label for="sort" class="text-xs font-medium text-slate-500 whitespace-nowrap">Urutkan:</label>
                <select id="sort" name="sort" onchange="this.form.submit()" 
                        class="text-xs font-medium bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A - Z</option>
                </select>
            </form>
        </div>

        <!-- Category Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <a href="{{ route('home', array_merge(request()->except('category', 'page'))) }}" 
               class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition {{ !request('category') ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:border-amber-400' }}">
                🐾 Semua Produk
            </a>
            @foreach($categories as $category)
                <a href="{{ route('home', array_merge(request()->except('category', 'page'), ['category' => $category->slug])) }}" 
                   class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition flex items-center gap-1.5 {{ request('category') == $category->slug ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:border-amber-400' }}">
                    <span>{{ $category->name }}</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('category') == $category->slug ? 'bg-amber-700 text-white' : 'bg-slate-100 text-slate-500' }}">
                        {{ $category->products_count }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Active Filter Indicator / Notice -->
    @if(request('category') || request('search'))
        <div class="flex items-center justify-between p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900">
            <div class="flex items-center gap-2 flex-wrap">
                <span>Filter aktif:</span>
                @if(request('category'))
                    <span class="font-bold bg-white px-2 py-0.5 rounded border border-amber-300">
                        Kategori: {{ $selectedCategory ? $selectedCategory->name : request('category') }}
                    </span>
                @endif
                @if(request('search'))
                    <span class="font-bold bg-white px-2 py-0.5 rounded border border-amber-300">
                        Kata Kunci: "{{ request('search') }}"
                    </span>
                @endif
            </div>
            <a href="{{ route('home') }}" class="text-amber-700 hover:underline font-semibold shrink-0">
                Reset Filter ✕
            </a>
        </div>
    @endif

    <!-- Product Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-amber-300 transition duration-200 flex flex-col group">
                    
                    <!-- Product Image -->
                    <div class="relative aspect-square bg-slate-100 overflow-hidden">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <!-- Badges Overlay -->
                        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 items-start">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold tracking-wide uppercase bg-slate-900/80 backdrop-blur-md text-white">
                                {{ $product->category->name }}
                            </span>
                        </div>

                        <!-- Stock Badge Overlay -->
                        <div class="absolute top-2.5 right-2.5">
                            @if($product->stock <= 0)
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase bg-rose-600 text-white shadow-sm">
                                    Stok Habis
                                </span>
                            @elseif($product->stock <= 5)
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-500 text-white shadow-sm">
                                    Sisa {{ $product->stock }} unit!
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Content -->
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="text-[11px] font-mono text-slate-400 mb-1">
                            SKU: {{ $product->sku }}
                        </div>
                        
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition line-clamp-2 mb-2 leading-snug">
                            <a href="{{ route('products.show', $product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <!-- Weight Info for Logistics -->
                        <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-3">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            <span>Berat: <strong class="text-slate-700">{{ $product->formatted_weight }}</strong></span>
                        </div>

                        <!-- Price & Action -->
                        <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-xs text-slate-400 block leading-none">Harga</span>
                                <span class="text-base font-extrabold text-amber-700">
                                    {{ $product->formatted_price }}
                                </span>
                            </div>

                            @if($product->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-3 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white text-xs font-bold shadow-xs hover:shadow-md transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Beli</span>
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled
                                        class="px-3 py-2 rounded-xl bg-slate-200 text-slate-400 text-xs font-bold cursor-not-allowed">
                                    Habis
                                </button>
                            @endif
                        </div>

                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                🐾
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Produk Tidak Ditemukan</h3>
            <p class="text-sm text-slate-500 mb-6">
                Tidak ada produk yang cocok dengan kriteria pencarian atau kategori yang Anda pilih.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-xl text-xs font-semibold hover:bg-amber-700 transition">
                Kembali ke Semua Produk
            </a>
        </div>
    @endif

</div>
@endsection
