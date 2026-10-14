<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected ShippingService $shippingService;

    public function __construct(CartService $cartService, ShippingService $shippingService)
    {
        $this->cartService = $cartService;
        $this->shippingService = $shippingService;
    }

    /**
     * Tampilkan formulir checkout guest.
     */
    public function index()
    {
        $cartData = $this->cartService->getDetails();

        if (empty($cartData['items'])) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $rates = $this->shippingService->getAvailableRates($cartData['total_weight_grams']);

        return view('storefront.checkout', compact('cartData', 'rates'));
    }

    /**
     * API / AJAX kalkulasi tarif kurir jika pembeli mengubah kota tujuan.
     */
    public function calculateShipping(Request $request)
    {
        $cartData = $this->cartService->getDetails();
        $city = $request->get('city', '');
        $rates = $this->shippingService->getAvailableRates($cartData['total_weight_grams'], $city);

        return response()->json([
            'rates' => $rates,
            'total_weight_grams' => $cartData['total_weight_grams'],
            'formatted_weight' => number_format($cartData['total_weight_grams'], 0, ',', '.') . ' gr',
            'subtotal' => $cartData['subtotal'],
            'formatted_subtotal' => 'Rp ' . number_format($cartData['subtotal'], 0, ',', '.'),
        ]);
    }

    /**
     * Proses pembuatan pesanan guest dalam DB Transaction.
     */
    public function store(Request $request)
    {
        $cartData = $this->cartService->getDetails();

        if (empty($cartData['items'])) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:150',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'required|string|max:30',
            'shipping_address' => 'required|string|max:500',
            'city_or_district' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'courier_option' => 'required|string', // Format: courier_code|service
            'payment_method' => 'required|string|in:mock_gateway,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        [$courierCode, $courierService] = explode('|', $validated['courier_option']);

        $shippingCost = $this->shippingService->getRate(
            $courierCode,
            $courierService,
            $cartData['total_weight_grams'],
            $validated['city_or_district']
        );

        if ($shippingCost === null) {
            return back()->withInput()->with('error', 'Opsi kurir pengiriman tidak valid.');
        }

        try {
            $order = DB::transaction(function () use ($validated, $cartData, $courierCode, $courierService, $shippingCost) {
                // 1. Validasi ulang ketersediaan stok produk & lock row
                $itemsToSave = [];

                foreach ($cartData['items'] as $item) {
                    $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

                    if (!$product || !$product->is_active) {
                        throw new \Exception("Produk '{$item['product']->name}' saat ini tidak tersedia.");
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Maaf, stok untuk '{$product->name}' tersisa {$product->stock} unit. Silakan sesuaikan jumlah di keranjang belanja.");
                    }

                    // Potong stok produk
                    $product->decrement('stock', $item['quantity']);

                    $itemsToSave[] = [
                        'product_id' => $product->id,
                        'product_name_snapshot' => $product->name,
                        'price_snapshot' => $product->price,
                        'weight_grams_snapshot' => $product->weight_grams,
                        'quantity' => $item['quantity'],
                        'subtotal' => $product->price * $item['quantity'],
                    ];
                }

                $subtotal = $cartData['subtotal'];
                $grandTotal = $subtotal + $shippingCost;

                // 2. Buat record Order
                $order = Order::create([
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'city_or_district' => $validated['city_or_district'],
                    'postal_code' => $validated['postal_code'],
                    'total_weight_grams' => $cartData['total_weight_grams'],
                    'courier_code' => $courierCode,
                    'courier_service' => $courierService,
                    'shipping_cost' => $shippingCost,
                    'tracking_number' => null,
                    'subtotal' => $subtotal,
                    'grand_total' => $grandTotal,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'unpaid',
                    'order_status' => 'pending',
                    'notes' => $validated['notes'] ?? null,
                ]);

                // 3. Simpan snapshot item pesanan
                foreach ($itemsToSave as $itemData) {
                    $itemData['order_id'] = $order->id;
                    OrderItem::create($itemData);
                }

                return $order;
            });

            // 4. Kosongkan session keranjang
            $this->cartService->clear();

            // 5. Redirect ke Invoice Publik
            return redirect()->route('orders.show', [
                'order_number' => $order->order_number,
                'token' => $order->tracking_token,
            ])->with('success', 'Pesanan Anda berhasil dibuat! Silakan lanjutkan pembayaran.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
