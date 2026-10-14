<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Tampilkan halaman invoice publik pesanan guest.
     * Menggunakan validasi tracking_token tanpa perlu login.
     */
    public function show(string $orderNumber, Request $request)
    {
        $token = $request->query('token');

        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Validasi keamanan: Token wajib cocok untuk akses guest
        if (!$token || $order->tracking_token !== $token) {
            abort(403, 'Akses ditolak. Token akses invoice tidak valid.');
        }

        return view('storefront.invoice', compact('order', 'token'));
    }

    /**
     * Simulasi Pembayaran (Stub) menggunakan PaymentService.
     */
    public function mockPayment(string $orderNumber, Request $request)
    {
        $token = $request->input('token');

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if (!$token || $order->tracking_token !== $token) {
            abort(403, 'Akses ditolak.');
        }

        if ($order->payment_status === 'paid') {
            return back()->with('info', 'Pesanan ini sudah berstatus lunas sebelumnya.');
        }

        // Jalankan stub PaymentService
        $this->paymentService->markAsPaid($order);

        return redirect()->route('orders.show', [
            'order_number' => $order->order_number,
            'token' => $order->tracking_token,
        ])->with('success', 'Simulasi Pembayaran Berhasil! Status pesanan kini beralih ke "Diproses". Admin kami akan segera menyiapkan paket anabul Anda.');
    }

    /**
     * Formulir Lacak Pesanan Mandiri untuk Guest.
     */
    public function trackForm()
    {
        return view('storefront.track');
    }

    /**
     * Proses pencarian pesanan berdasarkan Nomor Pesanan & Nomor WhatsApp.
     */
    public function trackSubmit(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string',
            'customer_phone' => 'required|string',
        ]);

        $orderNumber = trim($validated['order_number']);
        $customerPhone = trim($validated['customer_phone']);

        $order = Order::where('order_number', $orderNumber)
            ->where('customer_phone', $customerPhone)
            ->first();

        if (!$order) {
            return back()->withInput()->with('error', 'Pesanan tidak ditemukan. Mohon pastikan Nomor Pesanan dan Nomor WhatsApp sama persis dengan yang dimasukkan saat checkout.');
        }

        return redirect()->route('orders.show', [
            'order_number' => $order->order_number,
            'token' => $order->tracking_token,
        ])->with('success', 'Pesanan berhasil ditemukan.');
    }
}
