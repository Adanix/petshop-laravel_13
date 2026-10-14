@extends('layouts.app')

@section('title', $product->name . ' - PawPalace Petshop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('home', ['category' => $product->category->slug]) }}" class="hover:text-amber-600 transition">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-slate-800 font-medium truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Product Main Info Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 p-6 sm:p-10">
            
            <!-- Left: Product Image -->
            <div class="space-y-4">
                <div class="relative aspect-square rounded-2xl bg-slate-100 overflow-hidden border border-slate-100 shadow-inner">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    <!-- Stock Status Tag -->
                    <div class="absolute top-4 right-4">
                        @if($product->stock <= 0)
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-600 text-white shadow-md">
                                Stok Habis
                            </span>
                        @elseif($product->stock <= 5)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white shadow-md">
                                Sisa {{ $product->stock }} Unit!
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-md">
                                Stok Tersedia ({{ $product->stock }})
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Guarantee Badges -->
                <div class="grid grid-cols-3 gap-1.5 sm:gap-3 pt-2 text-center">
                    <div class="p-2 sm:p-2.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="block text-sm sm:text-base">🛡️</span>
                        <span class="text-[10px] sm:text-[11px] font-semibold text-slate-700">100% Original</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="block text-sm sm:text-base">📦</span>
                        <span class="text-[10px] sm:text-[11px] font-semibold text-slate-700">Packing Rapi</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="block text-sm sm:text-base">⚡</span>
                        <span class="text-[10px] sm:text-[11px] font-semibold text-slate-700">Kirim Hari Ini</span>
                    </div>
                </div>
            </div>

            <!-- Right: Product Information & Purchase Form -->
            <div class="flex flex-col justify-between space-y-6">
                <div>
                    <!-- Category & SKU -->
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                            {{ $product->category->name }}
                        </span>
                        <span class="text-xs font-mono text-slate-400">
                            SKU: {{ $product->sku }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight mb-4">
                        {{ $product->name }}
                    </h1>

                    <!-- Price -->
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/70 mb-6 flex flex-wrap items-baseline gap-2 sm:gap-3">
                        <span class="text-xs text-amber-800 font-semibold uppercase">Harga Pas:</span>
                        <span class="text-2xl sm:text-3xl font-black text-amber-800">
                            {{ $product->formatted_price }}
                        </span>
                    </div>

                    <!-- Logistics Weight Info Specification -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 mb-6">
                        <div class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            <span>Informasi Logistik & Pengiriman</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-slate-500 block">Berat Bersih / Ekspedisi:</span>
                                <strong class="text-slate-900 text-sm font-bold">{{ $product->formatted_weight }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Stok Real-time:</span>
                                <strong class="text-slate-900 text-sm font-bold">{{ $product->stock }} Unit</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Form -->
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="space-y-4" x-data="{ qty: 1, maxStock: {{ $product->stock }} }">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                <label class="text-xs font-bold text-slate-700">Jumlah Pembelian:</label>
                                <div class="inline-flex items-center border border-slate-300 rounded-xl bg-white shadow-xs">
                                    <button type="button" @click="if(qty > 1) qty--" 
                                            class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-l-xl transition font-bold min-h-[40px]">
                                        -
                                    </button>
                                    <input type="number" name="quantity" x-model.number="qty" min="1" :max="maxStock" 
                                           class="w-14 text-center text-sm font-bold border-none focus:outline-none focus:ring-0 p-0 text-slate-800">
                                    <button type="button" @click="if(qty < maxStock) qty++" 
                                            class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-r-xl transition font-bold min-h-[40px]">
                                        +
                                    </button>
                                </div>
                                <span class="text-xs text-slate-400">
                                    Maksimal {{ $product->stock }} unit
                                </span>
                            </div>

                            <button type="submit" 
                                    class="w-full min-h-[48px] py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-sm sm:text-base shadow-lg shadow-amber-500/25 flex items-center justify-center gap-2 transition transform active:scale-[0.99]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Tambah ke Keranjang Belanja</span>
                            </button>
                        </form>
                    @else
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-center">
                            <span class="text-rose-700 font-bold text-sm block mb-1">Stok Produk Sedang Kosong</span>
                            <span class="text-xs text-rose-500">Kami sedang melakukan restock segera. Silakan cek produk sejenis lainnya.</span>
                        </div>
                    @endif
                </div>

                <!-- Description Section -->
                <div class="pt-6 border-t border-slate-200">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">Deskripsi Produk</h3>
                    <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="space-y-4 pt-6">
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Produk Terkait di Kategori Ini</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 hover:shadow-md transition">
                        <a href="{{ route('products.show', $rel->slug) }}" class="block aspect-square bg-slate-100 rounded-xl overflow-hidden mb-3">
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover">
                        </a>
                        <h4 class="text-xs font-bold text-slate-800 line-clamp-1 mb-1">
                            <a href="{{ route('products.show', $rel->slug) }}" class="hover:text-amber-600">{{ $rel->name }}</a>
                        </h4>
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-extrabold text-amber-700">{{ $rel->formatted_price }}</span>
                            <span class="text-slate-400">{{ $rel->formatted_weight }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
