<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

// Redirect /login fallback to admin login
Route::get('/login', fn() => redirect()->route('admin.login'))->name('login');

/*
|--------------------------------------------------------------------------
| Public Storefront (Guest-First Architecture, Zero-Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/products/{slug}', [StorefrontController::class, 'show'])->name('products.show');

// Shopping Cart (Laravel Session)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove/{productId}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// Checkout & Shipping calculation
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::post('/rates', [CheckoutController::class, 'calculateShipping'])->name('rates');
});

// Guest Orders & Invoices (Public with Tracking Token)
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/{order_number}', [OrderTrackingController::class, 'show'])->name('show');
    Route::post('/{order_number}/mock-payment', [OrderTrackingController::class, 'mockPayment'])->name('mock-payment');
});

// Guest Order Lookup (No login required)
Route::get('/track-order', [OrderTrackingController::class, 'trackForm'])->name('orders.track-form');
Route::post('/track-order', [OrderTrackingController::class, 'trackSubmit'])->name('orders.track-submit');

/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Admin Routes (auth)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Products CRUD
        Route::resource('products', AdminProductController::class)->except(['show']);

        // Categories CRUD
        Route::resource('categories', AdminCategoryController::class)->only(['index', 'store', 'update', 'destroy']);

        // Orders Management & Logistics
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');
        Route::post('/orders/{order}/tracking', [AdminOrderController::class, 'updateTracking'])->name('orders.update-tracking');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    });
});
