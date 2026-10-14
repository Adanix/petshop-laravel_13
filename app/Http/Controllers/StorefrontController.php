<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    /**
     * Tampilkan katalog produk di halaman beranda dengan filter kategori dan pencarian.
     */
    public function index(Request $request)
    {
        $categories = Category::active()->withCount(['products' => function ($query) {
            $query->active();
        }])->get();

        $query = Product::active()->with('category');

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Pencarian nama produk atau deskripsi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $selectedCategory = $request->category ? Category::where('slug', $request->category)->first() : null;

        return view('storefront.index', compact('categories', 'products', 'selectedCategory'));
    }

    /**
     * Tampilkan detail produk.
     */
    public function show(string $slug)
    {
        $product = Product::active()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('storefront.show', compact('product', 'relatedProducts'));
    }
}
