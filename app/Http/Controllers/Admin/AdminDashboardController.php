<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class AdminDashboardController extends Controller
{
    /**
     * Halaman ikhtisar ringkasan statistik toko.
     */
    public function index()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');
        $ordersProcessingCount = Order::where('order_status', 'processing')->count();
        $ordersShippedCount = Order::where('order_status', 'shipped')->count();
        $lowStockProductsCount = Product::where('stock', '<=', 5)->count();

        $recentOrders = Order::latest()->take(6)->get();
        $lowStockProducts = Product::with('category')->where('stock', '<=', 5)->orderBy('stock', 'asc')->take(5)->get();

        $totalProductsCount = Product::count();
        $totalCategoriesCount = Category::count();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ordersProcessingCount',
            'ordersShippedCount',
            'lowStockProductsCount',
            'recentOrders',
            'lowStockProducts',
            'totalProductsCount',
            'totalCategoriesCount'
        ));
    }
}
