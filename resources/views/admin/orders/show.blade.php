@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_number)
@section('header_title', 'Kelola Pesanan: ' . $order->order_number)

@section('content')
<div class="space-y-6">

    <!-- Top Bar with Back & Public Invoice Link -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
            &larr; Kembali ke Daftar Pesanan
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('orders.show', ['order_number' => $order->order_number, 'token' => $order->tracking_token]) }}" target="_blank"
               class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Buka Invoice Publik Guest</span>
            </a>
        </div>
    </div>

    <!-- Main 2-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Order Details & Items (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Customer & Delivery Address Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3 flex flex-wrap items-center justify-between gap-2">
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Data Pembeli & Alamat Pengiriman</h3>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">
                        Guest Checkout (No-Login)
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-1">Nama Penerima</span>
                        <p class="text-sm font-bold text-slate-900">{{ $order->customer_name }}</p>

                        <span class="text-slate-400 block mt-3 mb-1">Kontak WhatsApp</span>
                        <p class="font-bold text-slate-800 font-mono text-sm">{{ $order->customer_phone }}</p>

                        <span class="text-slate-400 block mt-3 mb-1">Email Pembeli</span>
                        <p class="font-medium text-slate-700">{{ $order->customer_email }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">Alamat Pengiriman Lengkap</span>
                        <p class="font-medium text-slate-800 leading-relaxed">{{ $order->shipping_address }}</p>
                        <p class="font-bold text-slate-900 mt-1">{{ $order->city_or_district }} • Kode Pos: {{ $order->postal_code }}</p>

                        @if($order->notes)
                            <div class="mt-3 p-2.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900">
                                <strong>Catatan Pembeli:</strong> {{ $order->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ordered Items Table (Snapshot) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Item Pesanan (Snapshot Data)</h3>
                        <p class="text-xs text-slate-400">Harga dan bobot terkunci permanen saat pesanan dibuat</p>
                    </div>
                    <span class="text-xs font-bold text-slate-700">
                        Total Berat: {{ $order->formatted_weight }}
                    </span>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="min-w-[550px] w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-100 uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Produk Snapshot</th>
                                <th class="px-5 py-3">Berat Satuan</th>
                                <th class="px-5 py-3">Harga Satuan</th>
                                <th class="px-5 py-3 text-center">Qty</th>
                                <th class="px-5 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-5 py-3">
                                        <span class="font-bold text-slate-900 block">{{ $item->product_name_snapshot }}</span>
                                        @if($item->product)
                                            <span class="text-[11px] text-emerald-600 font-mono">Sisa stok inventaris saat ini: {{ $item->product->stock }} unit</span>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic">Produk asli telah dihapus dari katalog</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-slate-600">
                                        {{ $item->formatted_weight_snapshot }}
                                    </td>
                                    <td class="px-5 py-3 font-semibold text-slate-800">
                                        {{ $item->formatted_price_snapshot }}
                                    </td>
                                    <td class="px-5 py-3 text-center font-bold text-slate-900">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-5 py-3 text-right font-extrabold text-slate-900">
                                        {{ $item->formatted_subtotal }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financial Calculation -->
                <div class="p-4 sm:p-5 bg-slate-50 border-t border-slate-100 space-y-2 text-xs w-full sm:max-w-xs sm:ml-auto">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal Produk</span>
                        <span class="font-bold text-slate-900">{{ $order->formatted_subtotal }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Ongkir ({{ $order->courier_label }})</span>
                        <span class="font-bold text-slate-900">{{ $order->formatted_shipping_cost }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-200 flex justify-between items-baseline">
                        <span class="text-sm font-extrabold text-slate-900">Grand Total</span>
                        <span class="text-lg font-black text-amber-700">{{ $order->formatted_grand_total }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Actions & Logistics Management (1 Col) -->
        <div class="space-y-6">

            <!-- 1. Payment Verification Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                    Status Pembayaran
                </h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Metode:</span>
                        <strong class="text-slate-800">{{ $order->payment_method === 'mock_gateway' ? 'Payment Gateway' : 'Transfer Bank' }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status Bayar:</span>
                        @if($order->payment_status === 'paid')
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-[10px]">
                                Lunas (Paid)
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-extrabold text-[10px]">
                                Belum Bayar (Unpaid)
                            </span>
                        @endif
                    </div>
                    @if($order->paid_at)
                        <div class="flex justify-between">
                            <span class="text-slate-400">Waktu Lunas:</span>
                            <span class="text-slate-700 font-medium">{{ $order->paid_at->format('d/m/Y H:i') }} WIB</span>
                        </div>
                    @endif
                    @if($order->payment_reference)
                        <div class="flex justify-between">
                            <span class="text-slate-400">Referensi:</span>
                            <span class="font-mono text-slate-600">{{ $order->payment_reference }}</span>
                        </div>
                    @endif
                </div>

                @if($order->payment_status !== 'paid')
                    <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST"
                          onsubmit="return confirm('Verifikasi pembayaran pesanan ini sebagai LUNAS?');">
                        @csrf
                        <button type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Verifikasi Lunas (Mark as Paid)</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- 2. Logistics & Input Tracking Number Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-2 flex items-center justify-between">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">
                        Logistik & Resi Kurir
                    </h3>
                    <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-700 text-[10px] font-bold">
                        {{ $order->courier_label }}
                    </span>
                </div>

                <form action="{{ route('admin.orders.update-tracking', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Resi Pengiriman</label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" required
                               placeholder="Contoh: JNE8899776655ID"
                               class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                        <span class="text-[10px] text-slate-400 leading-tight block mt-1">
                            *Menyimpan resi otomatis mengubah status menjadi <strong>Shipped (Dikirim)</strong>.
                        </span>
                    </div>

                    <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs shadow-xs transition">
                        Simpan Nomor Resi & Set Dikirim
                    </button>
                </form>
            </div>

            <!-- 3. Change Order Status & Cancellation / Rollback Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                    Ubah Status Pesanan
                </h3>

                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-3"
                      onsubmit="if(document.getElementById('statusSelect').value === 'cancelled') { return confirm('PERHATIAN: Membatalkan pesanan akan mengembalikan stok produk ke inventaris secara otomatis. Lanjutkan?'); }">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Status Baru</label>
                        <select id="statusSelect" name="order_status" 
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-slate-800 font-semibold">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran)</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing (Sedang Disiapkan)</option>
                            <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)</option>
                            <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed (Selesai Diterima)</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled (Batalkan & Balikkan Stok)</option>
                        </select>
                    </div>

                    <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-xs transition">
                        Perbarui Status Pesanan
                    </button>
                </form>

                <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-[11px] text-rose-800 leading-relaxed">
                    💡 <strong>Aturan Bisnis:</strong> Memilih opsi <em>Cancelled</em> akan mengeksekusi <code>DB::transaction</code> untuk memulihkan stok seluruh item pesanan ke database secara akurat.
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
