@extends('layouts.app')

@section('title', 'Invoice Pesanan ' . $order->order_number . ' - PawPalace Petshop')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6">

    <!-- Top Alert with Shareable Link -->
    <div class="bg-amber-500/10 border border-amber-300 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-amber-900 uppercase tracking-wider block">Tautan Invoice Publik (Tanpa Login)</span>
            <p class="text-xs text-amber-800 mt-0.5">Simpan atau bookmark URL ini untuk memeriksa kembali status pesanan Anda sewaktu-waktu.</p>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto" x-data="{ copied: false }">
            <input type="text" readonly value="{{ url()->current() . '?token=' . $order->tracking_token }}" 
                   class="text-xs bg-white border border-amber-200 rounded-lg px-3 py-1.5 w-full sm:w-64 font-mono text-slate-600 truncate">
            <button type="button" @click="navigator.clipboard.writeText('{{ url()->current() . '?token=' . $order->tracking_token }}'); copied = true; setTimeout(() => copied = false, 2500)"
                    class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold shrink-0 transition">
                <span x-show="!copied">Salin URL</span>
                <span x-show="copied" class="text-amber-100">Tersalin! ✓</span>
            </button>
        </div>
    </div>

    <!-- Main Invoice Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-slate-900 to-slate-800 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-mono text-amber-400 font-semibold tracking-wide uppercase">INVOICE RESMI PESANAN</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-1">{{ $order->order_number }}</h1>
                <p class="text-xs text-slate-400 mt-1">Dipesan pada: {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
            
            <div class="flex flex-col sm:items-end gap-2">
                <!-- Payment Badge -->
                @if($order->payment_status === 'paid')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        LUNAS (PAID)
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        BELUM DIBAYAR (UNPAID)
                    </span>
                @endif

                <!-- Order Status Badge -->
                <span class="text-xs font-bold text-slate-300">
                    Status: <strong class="text-white uppercase">{{ $order->order_status }}</strong>
                </span>
            </div>
        </div>

        <!-- Order Stage Visual Tracker (Stepper) -->
        <div class="p-6 sm:p-8 bg-slate-50 border-b border-slate-200">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-6">Tahapan Pemrosesan Pesanan</h3>
            
            @php
                $stages = [
                    'pending' => ['label' => 'Pesanan Dibuat', 'desc' => 'Menunggu Pembayaran'],
                    'processing' => ['label' => 'Diproses', 'desc' => 'Paket Sedang Disiapkan'],
                    'shipped' => ['label' => 'Dikirimkan', 'desc' => 'Sedang Menuju Alamat'],
                    'completed' => ['label' => 'Selesai', 'desc' => 'Telah Diterima'],
                ];
                $statusOrder = ['pending' => 1, 'processing' => 2, 'shipped' => 3, 'completed' => 4];
                $currentStep = $statusOrder[$order->order_status] ?? 0;
            @endphp

            @if($order->order_status === 'cancelled')
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                    <svg class="w-6 h-6 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong>Pesanan Ini Telah Dibatalkan (Cancelled).</strong> Stok barang telah dikembalikan ke inventaris.
                    </div>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 relative">
                    @foreach($stages as $key => $st)
                        @php
                            $stepNum = $statusOrder[$key];
                            $isDone = $stepNum <= $currentStep;
                            $isCurrent = $stepNum === $currentStep;
                        @endphp
                        <div class="flex flex-col items-center text-center space-y-2">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition {{ $isDone ? 'bg-amber-600 text-white shadow-md' : 'bg-slate-200 text-slate-400' }}">
                                @if($stepNum < $currentStep)
                                    ✓
                                @else
                                    {{ $stepNum }}
                                @endif
                            </div>
                            <div>
                                <span class="text-xs font-bold block {{ $isCurrent ? 'text-amber-700' : ($isDone ? 'text-slate-800' : 'text-slate-400') }}">
                                    {{ $st['label'] }}
                                </span>
                                <span class="text-[10px] text-slate-400 leading-tight block">
                                    {{ $st['desc'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Nomor Resi Highlight (Jika Sudah Dikirim) -->
            @if($order->tracking_number)
                <div class="mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center">
                            🚚
                        </div>
                        <div>
                            <span class="text-xs font-bold text-emerald-900 block">Paket Sudah Berada di Ekspedisi</span>
                            <span class="text-xs text-emerald-700">Kurir: {{ $order->courier_label }}</span>
                        </div>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase block">Nomor Resi Resmi:</span>
                        <span class="text-base font-black font-mono text-emerald-800 tracking-wider">{{ $order->tracking_number }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Detail Pembeli & Pengiriman -->
        <div class="p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 text-sm">
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Informasi Pembeli (Guest)</h4>
                <p class="font-extrabold text-slate-900 text-base">{{ $order->customer_name }}</p>
                <p class="text-slate-600 mt-0.5">📞 {{ $order->customer_phone }} (WhatsApp)</p>
                <p class="text-slate-600">✉️ {{ $order->customer_email }}</p>
                @if($order->notes)
                    <div class="mt-2 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-200/60">
                        <strong>Catatan:</strong> {{ $order->notes }}
                    </div>
                @endif
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tujuan Pengiriman & Kurir</h4>
                <p class="text-slate-800 font-medium leading-relaxed">{{ $order->shipping_address }}</p>
                <p class="text-slate-600 mt-1">{{ $order->city_or_district }}, {{ $order->postal_code }}</p>
                <div class="mt-3 flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded bg-amber-50 text-amber-800 font-bold text-xs border border-amber-200">
                        {{ $order->courier_label }}
                    </span>
                    <span class="text-xs text-slate-400">Total Berat: {{ $order->formatted_weight }}</span>
                </div>
            </div>
        </div>

        <!-- Item Pesanan (Snapshot Data) -->
        <div class="p-6 sm:p-8">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Rincian Barang Pesanan</h4>
            <div class="divide-y divide-slate-100">
                @foreach($order->items as $item)
                    <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-xs">
                                🐾
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $item->product_name_snapshot }}</p>
                                <p class="text-xs text-slate-400">
                                    {{ $item->quantity }} x {{ $item->formatted_price_snapshot }} • {{ $item->formatted_weight_snapshot }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right font-extrabold text-slate-900">
                            {{ $item->formatted_subtotal }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Financial Details -->
            <div class="mt-6 pt-6 border-t border-slate-200 space-y-2 text-sm max-w-xs ml-auto">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal Produk</span>
                    <span class="font-bold text-slate-900">{{ $order->formatted_subtotal }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Ongkos Kirim ({{ $order->courier_label }})</span>
                    <span class="font-bold text-slate-900">{{ $order->formatted_shipping_cost }}</span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between items-baseline">
                    <span class="text-base font-extrabold text-slate-900">Total Tagihan</span>
                    <span class="text-2xl font-black text-amber-700">{{ $order->formatted_grand_total }}</span>
                </div>
            </div>
        </div>

        <!-- Mock Payment Section (Stub Sesuai Spesifikasi) -->
        @if($order->payment_status === 'unpaid' && $order->order_status !== 'cancelled')
            <div class="p-6 sm:p-8 bg-amber-50/80 border-t border-amber-200">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-extrabold text-amber-900">Lakukan Pembayaran Pesanan</h3>
                        <p class="text-xs text-amber-800 mt-1 max-w-lg">
                            Metode: <strong>{{ $order->payment_method === 'mock_gateway' ? 'Payment Gateway (QRIS / VA)' : 'Transfer Bank Manual' }}</strong>.
                            Klik tombol di samping untuk mensimulasikan pembayaran lunas secara instan.
                        </p>
                    </div>

                    <form action="{{ route('orders.mock-payment', $order->order_number) }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $order->tracking_token }}">
                        <button type="submit" 
                                class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-sm shadow-lg shadow-emerald-600/25 flex items-center gap-2 whitespace-nowrap transition transform active:scale-95">
                            <span>💳 Simulasi Bayar Sekarang (Mock Payment)</span>
                        </button>
                    </form>
                </div>
            </div>
        @elseif($order->payment_status === 'paid')
            <div class="p-6 sm:p-8 bg-emerald-50/80 border-t border-emerald-200 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold">✓</div>
                    <div>
                        <h3 class="text-sm font-extrabold text-emerald-900">Pembayaran Terkonfirmasi Lunas</h3>
                        <p class="text-xs text-emerald-700">Dibayar pada {{ $order->paid_at ? $order->paid_at->translatedFormat('d F Y, H:i') : '-' }} WIB • Ref: {{ $order->payment_reference ?? 'DIRECT' }}</p>
                    </div>
                </div>
                <a href="{{ route('home') }}" class="text-xs font-bold text-emerald-800 hover:underline">
                    Belanja Lagi &rarr;
                </a>
            </div>
        @endif

    </div>

</div>
@endsection
