@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('header_title', 'Kategori Produk')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Add Category Form (1 Col) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-900 text-sm">Tambah Kategori Baru</h3>
            <p class="text-xs text-slate-400">Buat klasifikasi produk kebutuhan anabul.</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="Contoh: Snack & Camilan"
                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                <textarea name="description" rows="3"
                          placeholder="Penjelasan produk dalam kategori ini..."
                          class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="cat_active" value="1" checked
                       class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                <label for="cat_active" class="text-xs font-semibold text-slate-700">Aktifkan di menu toko</label>
            </div>

            <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-xs transition">
                + Tambah Kategori
            </button>
        </form>
    </div>

    <!-- Categories List Table (2 Cols) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Daftar Kategori Produk</h3>
                <p class="text-xs text-slate-400">Total {{ $categories->count() }} kategori terdaftar</p>
            </div>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="min-w-[520px] w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3">Nama Kategori</th>
                        <th class="px-5 py-3">Slug</th>
                        <th class="px-5 py-3">Jumlah Produk</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700" x-data="{ editingId: null }">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-3">
                                <span class="font-bold text-slate-900 block">{{ $cat->name }}</span>
                                <span class="text-[11px] text-slate-400 line-clamp-1">{{ $cat->description }}</span>
                            </td>
                            <td class="px-5 py-3 font-mono text-slate-500">
                                {{ $cat->slug }}
                            </td>
                            <td class="px-5 py-3 font-semibold text-slate-900">
                                {{ $cat->products_count }} item
                            </td>
                            <td class="px-5 py-3">
                                @if($cat->is_active)
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold text-[10px]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-semibold text-[10px]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus kategori {{ $cat->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 min-h-[36px] bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-700 rounded-lg font-semibold text-xs transition"
                                                {{ $cat->products_count > 0 ? 'disabled title="Tidak bisa dihapus karena masih ada produk"' : '' }}>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                Belum ada kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
