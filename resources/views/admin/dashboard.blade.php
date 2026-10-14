@extends('layouts.admin')

@section('title', 'Dashboard Ringkasan')
@section('header_title', 'Ringkasan Statistik Toko')

@section('content')
<div class="space-y-6">

    <!-- 4 Main Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Metric 1: Total Pendapatan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pendapatan (Paid)</p>
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-0.5">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <!-- Metric 2: Pesanan Baru Perlu Diproses -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Perlu Diproses</p>
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-0.5">
                    {{ $ordersProcessingCount }} Pesanan
                </h3>
            </div>
        </div>

        <!-- Metric 3: Pesanan Dikirim -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sedang Dikirim</p>
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-0.5">
                    {{ $ordersShippedCount }} Paket
                </h3>
            </div>
        </div>

        <!-- Metric 4: Peringatan Stok Menipis (Stok <= 5) -->
        <div class="bg-white rounded-2xl border {{ $lowStockProductsCount > 0 ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200' }} p-4 sm:p-5 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $lowStockProductsCount > 0 ? 'bg-rose-100 text-rose-600 animate-pulse' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold {{ $lowStockProductsCount > 0 ? 'text-rose-600' : 'text-slate-400' }} uppercase tracking-wider">Stok Menipis (≤ 5)</p>
                <h3 class="text-lg sm:text-xl font-extrabold {{ $lowStockProductsCount > 0 ? 'text-rose-700' : 'text-slate-900' }} mt-0.5">
                    {{ $lowStockProductsCount }} Produk
                </h3>
            </div>
        </div>

    </div>

    <!-- Alert Banner If Low Stock Exists -->
    @if($lowStockProductsCount > 0)
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-300 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl shrink-0">⚠️</span>
                <div>
                    <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider">Perhatian Inventaris</h4>
                    <p class="text-xs text-amber-800">
                        Terdapat {{ $lowStockProductsCount }} produk yang stoknya habis atau menipis (≤ 5 unit). Segera lakukan restock agar pembeli tetap dapat memesan!
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.products.index', ['filter' => 'low_stock']) }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold shrink-0 transition self-end sm:self-auto min-h-[36px] flex items-center">
                Lihat Produk &rarr;
            </a>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Orders (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Pesanan Guest Terbaru</h3>
                    <p class="text-xs text-slate-400">Daftar transaksi checkout paling baru</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-amber-600 hover:underline">
                    Semua Pesanan &rarr;
                </a>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="min-w-[640px] w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">No. Pesanan</th>
                            <th class="px-5 py-3">Pembeli</th>
                            <th class="px-5 py-3">Kurir</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Bayar</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($recentOrders as $ord)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3 font-mono font-bold text-slate-900">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="hover:text-amber-600">
                                        {{ $ord->order_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="font-bold block text-slate-900">{{ $ord->customer_name }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $ord->customer_phone }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 font-semibold text-slate-700 uppercase text-[10px]">
                                        {{ $ord->courier_code }} - {{ $ord->courier_service }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 font-extrabold text-slate-900">
                                    {{ $ord->formatted_grand_total }}
                                </td>
                                <td class="px-5 py-3">
                                    @if($ord->payment_status === 'paid')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase
                                        @if($ord->order_status === 'pending') bg-slate-100 text-slate-700
                                        @elseif($ord->order_status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($ord->order_status === 'shipped') bg-purple-100 text-purple-800
                                        @elseif($ord->order_status === 'completed') bg-emerald-100 text-emerald-800
                                        @else bg-rose-100 text-rose-800 @endif">
                                        {{ $ord->order_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" 
                                       class="px-2.5 py-1 bg-slate-100 hover:bg-amber-100 text-slate-700 hover:text-amber-900 rounded font-bold text-[11px] transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada transaksi pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Products List (1 Col) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-sm">Produk Perlu Restock</h3>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">
                    ≤ 5 Unit
                </span>
            </div>

            <div class="space-y-3">
                @forelse($lowStockProducts as $low)
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                        <div class="min-w-0 pr-2">
                            <h4 class="font-bold text-slate-900 truncate">{{ $low->name }}</h4>
                            <p class="text-[11px] text-slate-400 font-mono">SKU: {{ $low->sku }} • {{ $low->formatted_weight }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($low->stock == 0)
                                <span class="px-2 py-0.5 rounded bg-rose-600 text-white font-extrabold text-[10px]">
                                    0 (Habis)
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-amber-500 text-white font-extrabold text-[10px]">
                                    {{ $low->stock }} Unit
                                </span>
                            @endif
                            <a href="{{ route('admin.products.edit', $low->id) }}" class="p-1 hover:text-amber-600" title="Edit Stok">
                                ✏️
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400">
                        Semua stok produk dalam kondisi aman (> 5 unit).
                    </div>
                @endforelse
            </div>

            <a href="{{ route('admin.products.create') }}" 
               class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs text-center block transition">
                + Tambah Produk Baru
            </a>
        </div>

    </div>

</div>
@endsection
