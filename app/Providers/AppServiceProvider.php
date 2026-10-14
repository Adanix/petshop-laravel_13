<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan hitungan item keranjang belanja ke seluruh view
        View::composer('*', function ($view) {
            $cart = session()->get('petshop_guest_cart', []);
            $view->with('globalCartCount', is_array($cart) ? array_sum($cart) : 0);
        });
    }
}
