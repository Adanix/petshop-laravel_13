@extends('layouts.admin')

@section('title', 'Edit Produk ' . $product->name)
@section('header_title', 'Perbarui Data Produk')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-3xl border border-slate-200 p-4 sm:p-8 shadow-xs space-y-6">
        
        <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Perbarui: {{ $product->name }}</h2>
                <p class="text-xs text-slate-500">Edit spesifikasi, stok, harga, dan bobot gram pengiriman.</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-bold">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Category & SKU -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Produk <span class="text-rose-500">*</span></label>
                    <select name="category_id" required
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode SKU Produk <span class="text-rose-500">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                           class="w-full px-3.5 py-2.5 text-sm font-mono bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    @error('sku') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Product Name & Slug -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Produk <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                           class="w-full px-3.5 py-2.5 text-sm font-mono bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    @error('slug') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Price, Weight, Stock (Strict Validations) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-amber-50/50 border border-amber-200/60">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga (Rupiah) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-slate-400 font-bold">Rp</span>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="1"
                               class="w-full pl-9 pr-3.5 py-2 text-sm bg-white border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    </div>
                    <span class="text-[10px] text-slate-400">Harus lebih besar dari 0</span>
                    @error('price') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Berat Gram (Logistik) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="weight_grams" value="{{ old('weight_grams', $product->weight_grams) }}" required min="1"
                               class="w-full pr-8 pl-3.5 py-2 text-sm bg-white border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-slate-400 font-bold">gr</span>
                    </div>
                    <span class="text-[10px] text-slate-400">1 Kg = 1000 gram</span>
                    @error('weight_grams') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Stok Inventaris <span class="text-rose-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                           class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    <span class="text-[10px] text-slate-400">Minimal 0 unit</span>
                    @error('stock') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Image URL -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">URL Gambar Produk</label>
                <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}"
                       class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                @if($product->image_url)
                    <div class="mt-2 flex items-center gap-3">
                        <img src="{{ $product->image_url }}" alt="Preview" class="w-12 h-12 object-cover rounded-lg border">
                        <span class="text-xs text-slate-500">Preview gambar saat ini</span>
                    </div>
                @endif
                @error('image_url') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi & Manfaat Produk <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="4" required
                          class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Active Checkbox -->
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                       class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                <label for="is_active" class="text-xs font-semibold text-slate-800">
                    Aktifkan produk ini di katalog toko (dapat dilihat dan dibeli pelanggan)
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-800">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
