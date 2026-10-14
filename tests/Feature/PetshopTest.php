<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\ShippingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetshopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_catalog_displays_products_and_categories(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Royal Canin Hair & Skin Care 2Kg');
        $response->assertSee('Whiskas Pouch Adult Tuna 80gr');
        $response->assertSee('Makanan Kucing');
        $response->assertSee('Stok Habis'); // Tas Ransel Astronot stok 0
    }

    public function test_filter_by_category(): void
    {
        $response = $this->get('/?category=makanan-kucing');

        $response->assertStatus(200);
        $response->assertSee('Royal Canin Hair & Skin Care 2Kg');
        $response->assertDontSee('Pedigree Adult Beef & Veg 1.5Kg');
    }

    public function test_product_detail_page(): void
    {
        $response = $this->get('/products/royal-canin-hair-skin-care-2kg');

        $response->assertStatus(200);
        $response->assertSee('RC-HS-2000');
        $response->assertSee('Rp 285.000');
        $response->assertSee('2.000 gr (2 Kg)');
        $response->assertSee('Tambah ke Keranjang Belanja');
    }

    public function test_cart_operations(): void
    {
        $product = Product::where('sku', 'RC-HS-2000')->first();

        // 1. Add to cart
        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $response->assertRedirect();
        $this->assertEquals(2, session('petshop_guest_cart')[$product->id]);

        // 2. View cart
        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertSee('Royal Canin Hair & Skin Care 2Kg');
        $response->assertSee('Rp 570.000');

        // 3. Update cart
        $this->post('/cart/update', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $this->assertEquals(1, session('petshop_guest_cart')[$product->id]);

        // 4. Remove from cart
        $this->post('/cart/remove/' . $product->id);
        $this->assertArrayNotHasKey($product->id, session('petshop_guest_cart', []));
    }

    public function test_guest_checkout_flow_and_stock_reduction(): void
    {
        $product = Product::where('sku', 'RC-HS-2000')->first();
        $initialStock = $product->stock;

        // Put in cart
        session(['petshop_guest_cart' => [$product->id => 2]]);

        // Submit checkout
        $response = $this->post('/checkout', [
            'customer_name' => 'Doni Darmawan',
            'customer_email' => 'doni@example.com',
            'customer_phone' => '081299887766',
            'shipping_address' => 'Jl. Gatot Subroto No. 99',
            'city_or_district' => 'Jakarta Selatan',
            'postal_code' => '12930',
            'courier_option' => 'jne|REG',
            'payment_method' => 'mock_gateway',
            'notes' => 'Tolong kirim siang hari',
        ]);

        $order = Order::where('customer_phone', '081299887766')->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('PET-', $order->order_number);
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('pending', $order->order_status);

        // Assert redirect to public invoice with tracking token
        $response->assertRedirect(route('orders.show', [
            'order_number' => $order->order_number,
            'token' => $order->tracking_token,
        ]));

        // Check stock reduced
        $product->refresh();
        $this->assertEquals($initialStock - 2, $product->stock);

        // Check cart emptied
        $this->assertEmpty(session('petshop_guest_cart'));
    }

    public function test_mock_payment_marks_order_as_paid_and_processing(): void
    {
        $order = Order::where('order_number', 'PET-20261014-B001')->first();
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('pending', $order->order_status);

        $response = $this->post(route('orders.mock-payment', $order->order_number), [
            'token' => $order->tracking_token,
        ]);

        $response->assertRedirect();
        $order->refresh();

        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('processing', $order->order_status);
        $this->assertNotNull($order->paid_at);
        $this->assertNotNull($order->payment_reference);
    }

    public function test_track_order_lookup(): void
    {
        $order = Order::where('order_number', 'PET-20261014-B001')->first();

        $response = $this->post('/track-order', [
            'order_number' => $order->order_number,
            'customer_phone' => $order->customer_phone,
        ]);

        $response->assertRedirect(route('orders.show', [
            'order_number' => $order->order_number,
            'token' => $order->tracking_token,
        ]));
    }

    public function test_admin_login_and_dashboard(): void
    {
        // Unauthenticated access redirects to login
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');

        // Login as admin
        $response = $this->post('/admin/login', [
            'email' => 'admin@petshop.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();

        // Dashboard accessible
        $response = $this->actingAs(User::where('email', 'admin@petshop.test')->first())
            ->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Statistik Toko');
        $response->assertSee('Total Pendapatan (Paid)');
        $response->assertSee('Stok Menipis');
    }

    public function test_admin_order_tracking_and_cancellation_stock_rollback(): void
    {
        $admin = User::where('email', 'admin@petshop.test')->first();
        $order = Order::where('order_number', 'PET-20261014-S002')->first();
        $item = $order->items->first();
        $product = Product::find($item->product_id);
        $initialStock = $product->stock;

        // 1. Admin inputs tracking number
        $response = $this->actingAs($admin)
            ->post(route('admin.orders.update-tracking', $order->id), [
                'tracking_number' => 'JNT9911223344ID',
            ]);

        $response->assertRedirect();
        $order->refresh();
        $this->assertEquals('shipped', $order->order_status);
        $this->assertEquals('JNT9911223344ID', $order->tracking_number);

        // 2. Admin cancels order -> stock must increment back
        $response = $this->actingAs($admin)
            ->post(route('admin.orders.update-status', $order->id), [
                'order_status' => 'cancelled',
            ]);

        $response->assertRedirect();
        $order->refresh();
        $this->assertEquals('cancelled', $order->order_status);

        $product->refresh();
        $this->assertEquals($initialStock + $item->quantity, $product->stock);
    }
}
