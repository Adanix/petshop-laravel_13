<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'petshop_guest_cart';

    /**
     * Ambil data keranjang saat ini beserta detail produk, subtotal, dan bobot.
     *
     * @return array
     */
    public function getDetails(): array
    {
        $rawCart = Session::get(self::SESSION_KEY, []);
        $items = [];
        $subtotal = 0;
        $totalWeight = 0;
        $totalQuantity = 0;

        if (!empty($rawCart)) {
            $productIds = array_keys($rawCart);
            $products = Product::whereIn('id', $productIds)
                ->where('is_active', true)
                ->get()
                ->keyBy('id');

            foreach ($rawCart as $productId => $qty) {
                if ($products->has($productId)) {
                    $product = $products->get($productId);
                    // Pastikan kuantitas tidak melebihi stok yang ada
                    $validQty = min($qty, $product->stock);
                    if ($validQty <= 0) {
                        continue;
                    }

                    $itemSubtotal = $product->price * $validQty;
                    $itemWeight = $product->weight_grams * $validQty;

                    $items[$productId] = [
                        'product_id' => $product->id,
                        'product' => $product,
                        'quantity' => $validQty,
                        'unit_price' => $product->price,
                        'subtotal' => $itemSubtotal,
                        'weight_grams' => $itemWeight,
                    ];

                    $subtotal += $itemSubtotal;
                    $totalWeight += $itemWeight;
                    $totalQuantity += $validQty;
                }
            }
        }

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'total_weight_grams' => $totalWeight,
            'total_quantity' => $totalQuantity,
        ];
    }

    /**
     * Tambah produk ke keranjang.
     *
     * @param int $productId
     * @param int $quantity
     * @return array [bool $success, string $message]
     */
    public function add(int $productId, int $quantity = 1): array
    {
        $product = Product::where('id', $productId)->where('is_active', true)->first();

        if (!$product) {
            return [false, 'Produk tidak ditemukan atau tidak aktif.'];
        }

        if ($product->stock <= 0) {
            return [false, 'Stok produk ini sedang habis.'];
        }

        $cart = Session::get(self::SESSION_KEY, []);
        $currentQty = $cart[$productId] ?? 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock) {
            return [false, "Hanya tersisa {$product->stock} unit untuk produk ini."];
        }

        $cart[$productId] = $newQty;
        Session::put(self::SESSION_KEY, $cart);

        return [true, "Berhasil menambahkan {$product->name} ke keranjang."];
    }

    /**
     * Update kuantitas produk di keranjang.
     *
     * @param int $productId
     * @param int $quantity
     * @return array [bool $success, string $message]
     */
    public function update(int $productId, int $quantity): array
    {
        $cart = Session::get(self::SESSION_KEY, []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
            Session::put(self::SESSION_KEY, $cart);
            return [true, 'Item berhasil dihapus dari keranjang.'];
        }

        $product = Product::where('id', $productId)->where('is_active', true)->first();
        if (!$product) {
            unset($cart[$productId]);
            Session::put(self::SESSION_KEY, $cart);
            return [false, 'Produk tidak ditemukan.'];
        }

        if ($quantity > $product->stock) {
            $cart[$productId] = $product->stock;
            Session::put(self::SESSION_KEY, $cart);
            return [false, "Kuantitas disesuaikan dengan stok maksimal ({$product->stock} unit)."];
        }

        $cart[$productId] = $quantity;
        Session::put(self::SESSION_KEY, $cart);

        return [true, 'Keranjang belanja berhasil diperbarui.'];
    }

    /**
     * Hapus produk dari keranjang.
     */
    public function remove(int $productId): void
    {
        $cart = Session::get(self::SESSION_KEY, []);
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Bersihkan keranjang.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Ambil total jumlah kuantitas item.
     */
    public function count(): int
    {
        $cart = Session::get(self::SESSION_KEY, []);
        return array_sum($cart);
    }
}
