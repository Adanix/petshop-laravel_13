<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Tampilkan keranjang belanja guest.
     */
    public function index()
    {
        $cartData = $this->cartService->getDetails();
        return view('storefront.cart', compact('cartData'));
    }

    /**
     * Tambahkan item ke keranjang.
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        [$success, $message] = $this->cartService->add($validated['product_id'], $quantity);

        if (!$success) {
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }

    /**
     * Update jumlah item di keranjang.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        [$success, $message] = $this->cartService->update($validated['product_id'], $validated['quantity']);

        if (!$success) {
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }

    /**
     * Hapus item dari keranjang.
     */
    public function remove(int $productId)
    {
        $this->cartService->remove($productId);
        return back()->with('success', 'Item berhasil dihapus dari keranjang belanja.');
    }

    /**
     * Kosongkan keranjang.
     */
    public function clear()
    {
        $this->cartService->clear();
        return redirect()->route('cart.index')->with('success', 'Keranjang belanja telah dikosongkan.');
    }
}
