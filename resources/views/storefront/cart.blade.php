@extends('layouts.app')

@section('title', 'Keranjang Belanja - PawPalace Petshop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Keranjang Belanja</h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Pemesanan Guest: Belanja langsung tanpa login akun.
            </p>
        </div>

        @if(!empty($cartData['items']))
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua barang dari keranjang belanja?');">
                @csrf
                <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline">
                    Kosongkan Keranjang
                </button>
            </form>
        @endif
    </div>

    @if(empty($cartData['items']))
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-lg mx-auto shadow-xs">
            <div class="w-20 h-20 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">Keranjang Belanja Masih Kosong</h2>
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                Belum ada produk anabul yang Anda pilih. Yuk lihat makanan lezat, vitamin, atau mainan menarik di katalog kami!
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm shadow-md transition">
                <span>Jelajahi Katalog Produk</span> &rarr;
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Items List -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xs divide-y divide-slate-100 overflow-hidden">
                    @foreach($cartData['items'] as $item)
                        @php
                            $prod = $item['product'];
                        @endphp
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <!-- Image -->
                            <div class="w-20 h-20 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-100">
                                @if($prod->image_url)
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">🐾</div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2 py-0.5 rounded">
                                    {{ $prod->category->name }}
                                </span>
                                <h3 class="text-sm font-bold text-slate-900 mt-1 line-clamp-1">
                                    <a href="{{ route('products.show', $prod->slug) }}" class="hover:text-amber-600">
                                        {{ $prod->name }}
                                    </a>
                                </h3>
                                <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                                    <span>Harga: <strong class="text-slate-700">{{ $prod->formatted_price }}</strong></span>
                                    <span>•</span>
                                    <span>Berat: <strong class="text-slate-700">{{ number_format($item['weight_grams'], 0, ',', '.') }} gr</strong></span>
                                </div>
                            </div>

                            <!-- Quantity Form & Controls -->
                            <div class="w-full sm:w-auto flex items-center justify-between sm:justify-end gap-3 sm:gap-4 pt-3 sm:pt-0 border-t border-slate-100 sm:border-0">
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                    <div class="inline-flex items-center border border-slate-300 rounded-lg bg-slate-50 text-xs">
                                        <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" 
                                                class="w-8 h-8 flex items-center justify-center font-bold text-slate-600 hover:bg-slate-200 rounded-l-lg transition">
                                            -
                                        </button>
                                        <span class="w-9 text-center font-bold text-slate-900">
                                            {{ $item['quantity'] }}
                                        </span>
                                        <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" 
                                                {{ $item['quantity'] >= $prod->stock ? 'disabled' : '' }}
                                                class="w-8 h-8 flex items-center justify-center font-bold text-slate-600 hover:bg-slate-200 rounded-r-lg transition {{ $item['quantity'] >= $prod->stock ? 'opacity-40 cursor-not-allowed' : '' }}">
                                            +
                                        </button>
                                    </div>
                                </form>

                                <!-- Subtotal per Item -->
                                <div class="text-right min-w-[80px] sm:min-w-[90px]">
                                    <div class="text-[11px] text-slate-400">Total</div>
                                    <div class="text-xs sm:text-sm font-extrabold text-amber-700">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Remove Item -->
                                <form action="{{ route('cart.remove', $prod->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-lg transition min-h-[36px]" title="Hapus Item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="space-y-4">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-5">
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Ringkasan Belanja</h2>
                    
                    <div class="space-y-3 text-sm border-b border-slate-100 pb-4">
                        <div class="flex justify-between text-slate-600">
                            <span>Total Jumlah Item</span>
                            <span class="font-bold text-slate-900">{{ $cartData['total_quantity'] }} barang</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Berat Ekspedisi</span>
                            <span class="font-bold text-slate-900">
                                {{ number_format($cartData['total_weight_grams'], 0, ',', '.') }} gr 
                                ({{ round($cartData['total_weight_grams'] / 1000, 2) }} Kg)
                            </span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal Harga</span>
                            <span class="font-bold text-slate-900">
                                Rp {{ number_format($cartData['subtotal'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between items-baseline pt-1">
                        <div>
                            <span class="text-xs text-slate-500 block">Estimasi Total Produk</span>
                            <span class="text-xs text-amber-600">*Belum termasuk ongkos kirim</span>
                        </div>
                        <span class="text-xl font-black text-amber-700">
                            Rp {{ number_format($cartData['subtotal'], 0, ',', '.') }}
                        </span>
                    </div>

                    <a href="{{ route('checkout.index') }}" 
                       class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-center block text-sm shadow-lg shadow-amber-500/25 transition transform active:scale-[0.99]">
                        Lanjut ke Checkout & Pengiriman &rarr;
                    </a>

                    <div class="text-[11px] text-slate-400 text-center flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Guest Checkout aman • Data terlindungi</span>
                    </div>
                </div>

                <a href="{{ route('home') }}" class="block text-center text-xs font-semibold text-slate-500 hover:text-amber-600">
                    &larr; Lanjut Berbelanja Produk Lain
                </a>
            </div>

        </div>
    @endif

</div>
@endsection
