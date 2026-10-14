@extends('layouts.admin')

@section('title', 'Kelola Pesanan & Logistik')
@section('header_title', 'Daftar Pesanan & Pengiriman')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari no. pesanan, nama, nomor telepon, resi..."
                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div>
                <select name="order_status" onchange="this.form.submit()"
                        class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-slate-700">
                    <option value="">Semua Status Pesanan</option>
                    <option value="pending" {{ request('order_status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)</option>
                    <option value="processing" {{ request('order_status') === 'processing' ? 'selected' : '' }}>Processing (Perlu Dikirim)</option>
                    <option value="shipped" {{ request('order_status') === 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)</option>
                    <option value="completed" {{ request('order_status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    <option value="cancelled" {{ request('order_status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                </select>
            </div>

            <div>
                <select name="payment_status" onchange="this.form.submit()"
                        class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-slate-700">
                    <option value="">Semua Status Bayar</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid (Belum Lunas)</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                </select>
            </div>

            <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white rounded-lg text-xs font-semibold hover:bg-slate-700 transition">
                Terapkan
            </button>

            @if(request('search') || request('order_status') || request('payment_status'))
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No. Pesanan</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Pembeli (Guest)</th>
                        <th class="px-5 py-3.5">Kurir & Berat</th>
                        <th class="px-5 py-3.5">Nomor Resi</th>
                        <th class="px-5 py-3.5">Total Tagihan</th>
                        <th class="px-5 py-3.5">Pembayaran</th>
                        <th class="px-5 py-3.5">Status Pesanan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Order Number -->
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="font-mono font-bold text-amber-700 hover:underline">
                                    {{ $ord->order_number }}
                                </a>
                                <span class="block text-[10px] text-slate-400 font-mono">Token: {{ substr($ord->tracking_token, 0, 8) }}...</span>
                            </td>

                            <!-- Date -->
                            <td class="px-5 py-3 text-slate-500 whitespace-nowrap">
                                {{ $ord->created_at->format('d/m/Y H:i') }}
                            </td>

                            <!-- Customer -->
                            <td class="px-5 py-3">
                                <span class="font-bold text-slate-900 block">{{ $ord->customer_name }}</span>
                                <span class="text-[11px] text-slate-500 font-mono">{{ $ord->customer_phone }}</span>
                            </td>

                            <!-- Logistics -->
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded bg-slate-100 font-semibold text-slate-700 text-[10px]">
                                    {{ $ord->courier_label }}
                                </span>
                                <span class="block text-[11px] text-slate-400 mt-0.5">{{ $ord->formatted_weight }}</span>
                            </td>

                            <!-- Tracking Number -->
                            <td class="px-5 py-3 font-mono">
                                @if($ord->tracking_number)
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 rounded font-bold border border-emerald-200">
                                        {{ $ord->tracking_number }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Belum diinput</span>
                                @endif
                            </td>

                            <!-- Grand Total -->
                            <td class="px-5 py-3 font-extrabold text-slate-900 whitespace-nowrap">
                                {{ $ord->formatted_grand_total }}
                            </td>

                            <!-- Payment Status -->
                            <td class="px-5 py-3">
                                @if($ord->payment_status === 'paid')
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                                        Lunas
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>

                            <!-- Order Status -->
                            <td class="px-5 py-3">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase
                                    @if($ord->order_status === 'pending') bg-slate-100 text-slate-700
                                    @elseif($ord->order_status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($ord->order_status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($ord->order_status === 'completed') bg-emerald-100 text-emerald-800
                                    @else bg-rose-100 text-rose-800 @endif">
                                    {{ $ord->order_status }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" 
                                   class="px-3 py-1 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded font-bold text-[11px] transition">
                                    Kelola &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center text-slate-400">
                                Tidak ada data pesanan yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
