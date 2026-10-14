@extends('layouts.app')

@section('title', 'Checkout Pengiriman - PawPalace Petshop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8" 
     x-data="{
        subtotal: {{ $cartData['subtotal'] }},
        weightGrams: {{ $cartData['total_weight_grams'] }},
        selectedCourier: '{{ $rates[0]['courier_code'] }}|{{ $rates[0]['service'] }}',
        shippingCost: {{ $rates[0]['cost'] }},
        paymentMethod: 'mock_gateway',
        rates: {{ json_encode($rates) }},
        selectRate(rate) {
            this.selectedCourier = rate.courier_code + '|' + rate.service;
            this.shippingCost = rate.cost;
        },
        get grandTotal() {
            return this.subtotal + this.shippingCost;
        },
        formatRupiah(val) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
        }
     }">

    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Checkout Pesanan (Guest)</h1>
        <p class="text-xs sm:text-sm text-slate-500">
            Lengkapi data pengiriman tanpa perlu login. Konfirmasi pesanan dan nomor pelacakan akan dikirimkan ke WhatsApp & Email Anda.
        </p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Form Data Pembeli & Kurir & Pembayaran -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 1. Biodata Pembeli -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">1</div>
                        <h2 class="text-base font-bold text-slate-900">Data Penerima & Kontak</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Penerima <span class="text-rose-500">*</span></label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                                   placeholder="Contoh: Budi Santoso"
                                   class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                            @error('customer_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp Aktif <span class="text-rose-500">*</span></label>
                            <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required
                                   placeholder="081234567890"
                                   class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                            <span class="text-[11px] text-slate-400">Untuk notifikasi status resi kurir</span>
                            @error('customer_phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                                   placeholder="budi@example.com"
                                   class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                            <span class="text-[11px] text-slate-400">Invoice dikirimkan ke email ini</span>
                            @error('customer_email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Alamat Pengiriman -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">2</div>
                        <h2 class="text-base font-bold text-slate-900">Alamat Tujuan Pengiriman</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap (Jalan, No. Rumah, RT/RW, Patokan) <span class="text-rose-500">*</span></label>
                            <textarea name="shipping_address" rows="3" required
                                      placeholder="Contoh: Jl. Anggrek No. 45 RT 02/08, Blok B, Pagar Hitam"
                                      class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Kota / Kecamatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="city_or_district" value="{{ old('city_or_district', 'Jakarta Selatan') }}" required
                                       placeholder="Contoh: Jakarta Selatan"
                                       class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                                @error('city_or_district') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Kode Pos <span class="text-rose-500">*</span></label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}" required
                                       placeholder="Contoh: 12190"
                                       class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                                @error('postal_code') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan untuk Penjual / Kurir (Opsional)</label>
                            <input type="text" name="notes" value="{{ old('notes') }}"
                                   placeholder="Contoh: Titipkan di satpam jika tidak ada orang di rumah"
                                   class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                        </div>
                    </div>
                </div>

                <!-- 3. Pilihan Kurir Logistik -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold shrink-0">3</div>
                            <h2 class="text-base font-bold text-slate-900">Pilihan Kurir & Tarif Pengiriman</h2>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 rounded-lg text-slate-700 self-start sm:self-auto">
                            Total Berat: {{ number_format($cartData['total_weight_grams'], 0, ',', '.') }} gr ({{ ceil($cartData['total_weight_grams']/1000) }} Kg dihitung)
                        </span>
                    </div>

                    <p class="text-xs text-slate-500">
                        Tarif dihitung otomatis berdasarkan berat riil produk menggunakan simulasi logistik cerdas.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <template x-for="rate in rates" :key="rate.courier_code + '|' + rate.service">
                            <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition"
                                   :class="selectedCourier === (rate.courier_code + '|' + rate.service) ? 'border-amber-500 bg-amber-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                <input type="radio" name="courier_option" 
                                       :value="rate.courier_code + '|' + rate.service"
                                       x-model="selectedCourier"
                                       @change="selectRate(rate)"
                                       class="sr-only">
                                
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-slate-900" x-text="rate.courier_name"></span>
                                    <span class="text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-slate-100 text-slate-700" x-text="rate.service"></span>
                                </div>
                                <span class="text-[11px] text-slate-500 mb-3" x-text="rate.description"></span>
                                
                                <div class="mt-auto pt-2 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs text-slate-400" x-text="rate.estimated_days"></span>
                                    <span class="text-sm font-black text-amber-700" x-text="formatRupiah(rate.cost)"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                    @error('courier_option') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- 4. Metode Pembayaran -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">4</div>
                        <h2 class="text-base font-bold text-slate-900">Metode Pembayaran</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition"
                               :class="paymentMethod === 'mock_gateway' ? 'border-amber-500 bg-amber-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white'">
                            <input type="radio" name="payment_method" value="mock_gateway" x-model="paymentMethod" class="mt-1 text-amber-600 focus:ring-amber-500">
                            <div>
                                <div class="text-sm font-bold text-slate-900">Simulasi Payment Gateway (Otomatis)</div>
                                <p class="text-xs text-slate-500 mt-0.5">Instant simulasi bayar QRIS / Virtual Account tanpa nunggu konfirmasi manual admin.</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition"
                               :class="paymentMethod === 'bank_transfer' ? 'border-amber-500 bg-amber-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white'">
                            <input type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod" class="mt-1 text-amber-600 focus:ring-amber-500">
                            <div>
                                <div class="text-sm font-bold text-slate-900">Transfer Bank Manual</div>
                                <p class="text-xs text-slate-500 mt-0.5">Transfer langsung ke rekening BCA / Mandiri toko, verifikasi manual oleh admin.</p>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Right Col: Order Summary & Action -->
            <div class="space-y-4">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-5 sticky top-24">
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Rincian Pembelian</h2>
                    
                    <!-- Items Mini List -->
                    <div class="space-y-3 max-h-60 overflow-y-auto pr-1 divide-y divide-slate-100">
                        @foreach($cartData['items'] as $item)
                            <div class="pt-2 first:pt-0 flex items-center justify-between text-xs">
                                <div class="min-w-0 pr-2">
                                    <p class="font-bold text-slate-800 truncate">{{ $item['product']->name }}</p>
                                    <p class="text-slate-400">{{ $item['quantity'] }}x @ Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</p>
                                </div>
                                <div class="font-extrabold text-slate-800 shrink-0">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Financial Summary -->
                    <div class="space-y-2.5 text-xs border-t border-slate-100 pt-4">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal Produk</span>
                            <span class="font-bold text-slate-900" x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Pengiriman</span>
                            <span class="font-bold text-amber-700" x-text="formatRupiah(shippingCost)"></span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Berat Paket</span>
                            <span class="font-semibold text-slate-700">{{ number_format($cartData['total_weight_grams'], 0, ',', '.') }} gr</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-3 flex justify-between items-baseline">
                        <span class="text-sm font-bold text-slate-900">Total Pembayaran</span>
                        <span class="text-2xl font-black text-amber-800" x-text="formatRupiah(grandTotal)"></span>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-extrabold text-sm shadow-xl shadow-amber-500/25 flex items-center justify-center gap-2 transition transform active:scale-[0.99]">
                        <span>🐾 Buat Pesanan Sekarang</span>
                    </button>

                    <div class="p-3 rounded-xl bg-amber-50 text-[11px] text-amber-900 border border-amber-200/60 leading-relaxed">
                        🔒 <strong>Zero-Login Guarantee:</strong> Setelah menekan tombol ini, stok produk langsung dipotong dan invoice publik dengan token unik Anda akan diterbitkan otomatis.
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
