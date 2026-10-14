<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Buat transaksi pembayaran untuk order.
     * 
     * // TODO: Integrasi API Midtrans / Xendit di tahap akhir
     *
     * @param Order $order
     * @return array
     */
    public function createTransaction(Order $order): array
    {
        // TODO: Integrasi API Midtrans / Xendit di tahap akhir

        $mockReference = 'MOCK-PAY-' . strtoupper(Str::random(12));
        
        $order->update([
            'payment_reference' => $mockReference,
        ]);

        return [
            'status' => 'success',
            'reference' => $mockReference,
            'redirect_url' => route('orders.show', [
                'order_number' => $order->order_number,
                'token' => $order->tracking_token,
            ]),
        ];
    }

    /**
     * Tandai pesanan sebagai telah dibayar (Lunas).
     * 
     * // TODO: Integrasi API Midtrans / Xendit di tahap akhir
     *
     * @param Order $order
     * @return bool
     */
    public function markAsPaid(Order $order): bool
    {
        // TODO: Integrasi API Midtrans / Xendit di tahap akhir

        if ($order->payment_status === 'paid') {
            return true;
        }

        $order->payment_status = 'paid';
        $order->paid_at = now();

        if ($order->order_status === 'pending') {
            $order->order_status = 'processing';
        }

        if (empty($order->payment_reference)) {
            $order->payment_reference = 'MOCK-CONFIRM-' . strtoupper(Str::random(10));
        }

        return $order->save();
    }
}
