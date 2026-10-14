@extends('layouts.app')

@section('title', 'Lacak Pesanan Mandiri - PawPalace Petshop')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 py-10 sm:py-16">

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-sm space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center mx-auto text-2xl shadow-md shadow-amber-500/20">
                🔍
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Cek & Lacak Pesanan Anda</h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto">
                Tanpa perlu login akun. Masukkan Nomor Pesanan dan Nomor WhatsApp yang digunakan saat memesan.
            </p>
        </div>

        <form action="{{ route('orders.track-submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Pesanan</label>
                <div class="relative">
                    <input type="text" name="order_number" value="{{ old('order_number') }}" required
                           placeholder="Contoh: PET-20261014-B001"
                           class="w-full pl-10 pr-4 py-2.5 text-sm font-mono bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                    </div>
                </div>
                <span class="text-[11px] text-slate-400">Format: PET-YYYYMMDD-XXXX (tercantum di struk atau invoice Anda)</span>
                @error('order_number') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp Pembeli</label>
                <div class="relative">
                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required
                           placeholder="Contoh: 081234567890"
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                </div>
                @error('customer_phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" 
                    class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-sm shadow-md transition transform active:scale-[0.99] flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Cari & Buka Pesanan</span>
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center">
            <span class="text-xs text-slate-400">Ingin melihat contoh? Coba cari pesanan seeder kami:</span>
            <div class="mt-2 flex flex-wrap justify-center gap-2 text-[11px] font-mono">
                <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600">PET-20261014-B001 (081234567890)</span>
                <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600">PET-20261014-S002 (081298765432)</span>
            </div>
        </div>

    </div>

</div>
@endsection
