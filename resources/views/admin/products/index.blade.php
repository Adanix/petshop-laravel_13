@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('header_title', 'Katalog & Inventaris Produk')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4">
        
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 w-full sm:w-auto flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama produk atau SKU..."
                   class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 w-full sm:w-64 min-h-[38px]">
            
            <select name="category_id" onchange="this.form.submit()"
                    class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-slate-700 min-h-[38px]">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-3 py-2 bg-slate-800 text-white rounded-lg text-xs font-semibold hover:bg-slate-700 transition min-h-[38px] flex items-center justify-center">
                Cari
            </button>

            @if(request('search') || request('category_id') || request('filter'))
                <a href="{{ route('admin.products.index') }}" class="text-xs text-rose-600 hover:underline ml-1 text-center py-1">
                    Reset
                </a>
            @endif
        </form>

        <a href="{{ route('admin.products.create') }}" 
           class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition shrink-0 flex items-center justify-center gap-1.5 min-h-[40px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Produk Baru</span>
        </a>

    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="min-w-[720px] w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Produk</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Harga</th>
                        <th class="px-5 py-3.5">Berat (Logistik)</th>
                        <th class="px-5 py-3.5">Stok Unit</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($products as $prod)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Image & Name & SKU -->
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                        @if($prod->image_url)
                                            <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">🐾</div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <h4 class="font-bold text-slate-900 truncate">{{ $prod->name }}</h4>
                                        <p class="text-[11px] font-mono text-slate-400">SKU: {{ $prod->sku }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 font-semibold text-[11px]">
                                    {{ $prod->category->name }}
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="px-5 py-3 font-extrabold text-slate-900">
                                {{ $prod->formatted_price }}
                            </td>

                            <!-- Weight (Logistics) -->
                            <td class="px-5 py-3">
                                <span class="font-semibold text-slate-800">{{ $prod->formatted_weight }}</span>
                            </td>

                            <!-- Stock Indicator -->
                            <td class="px-5 py-3">
                                @if($prod->stock <= 0)
                                    <span class="px-2.5 py-1 rounded-md bg-rose-100 text-rose-800 font-extrabold text-[10px]">
                                        0 (Habis)
                                    </span>
                                @elseif($prod->stock <= 5)
                                    <span class="px-2.5 py-1 rounded-md bg-amber-100 text-amber-800 font-bold text-[10px]">
                                        {{ $prod->stock }} Unit (Menipis)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                                        {{ $prod->stock }} Unit
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-3">
                                @if($prod->is_active)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $prod->id) }}" 
                                       class="px-2.5 py-1 bg-slate-100 hover:bg-amber-100 text-slate-700 hover:text-amber-900 rounded font-semibold text-[11px] transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus produk {{ $prod->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-2.5 py-1 bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-700 rounded font-semibold text-[11px] transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                Belum ada data produk yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
