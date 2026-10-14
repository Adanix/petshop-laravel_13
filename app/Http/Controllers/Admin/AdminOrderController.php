<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Tampilkan daftar pesanan dengan filter status pesanan & status pembayaran.
     */
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan rincian pesanan.
     */
    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Verifikasi pembayaran manual oleh admin (ubah unpaid -> paid).
     */
    public function verifyPayment(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => $order->paid_at ?? now(),
            'order_status' => ($order->order_status === 'pending') ? 'processing' : $order->order_status,
        ]);

        return back()->with('success', "Pembayaran untuk pesanan {$order->order_number} berhasil diverifikasi Lunas.");
    }

    /**
     * Input / update nomor resi pengiriman.
     * Otomatis mengubah order_status menjadi 'shipped'.
     */
    public function updateTracking(Request $request, Order $order)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        $order->update([
            'tracking_number' => $validated['tracking_number'],
            'order_status' => 'shipped',
        ]);

        return back()->with('success', "Nomor resi {$validated['tracking_number']} berhasil disimpan. Status pesanan otomatis diubah menjadi 'Dikirim (Shipped)'.");
    }

    /**
     * Ubah status pesanan (pending, processing, shipped, completed, cancelled).
     * Jika status diubah menjadi 'cancelled', kembalikan stok produk secara otomatis di dalam DB Transaction.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $newStatus = $validated['order_status'];
        $oldStatus = $order->order_status;

        if ($newStatus === $oldStatus) {
            return back()->with('info', 'Status pesanan tidak berubah.');
        }

        try {
            DB::transaction(function () use ($order, $newStatus, $oldStatus) {
                // Jika dibatalkan dan sebelumnya belum dibatalkan -> kembalikan stok
                if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                    foreach ($order->items as $item) {
                        if ($item->product_id) {
                            $product = Product::find($item->product_id);
                            if ($product) {
                                $product->increment('stock', $item->quantity);
                            }
                        }
                    }
                }

                // Jika sebelumnya dibatalkan lalu diaktifkan kembali -> kurangi stok kembali jika mencukupi
                if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                    foreach ($order->items as $item) {
                        if ($item->product_id) {
                            $product = Product::find($item->product_id);
                            if ($product) {
                                if ($product->stock < $item->quantity) {
                                    throw new \Exception("Gagal mengaktifkan kembali: stok produk '{$product->name}' tidak mencukupi (sisa: {$product->stock}).");
                                }
                                $product->decrement('stock', $item->quantity);
                            }
                        }
                    }
                }

                $order->update([
                    'order_status' => $newStatus,
                ]);
            });

            $msg = "Status pesanan {$order->order_number} berhasil diperbarui menjadi '" . ucfirst($newStatus) . "'.";
            if ($newStatus === 'cancelled') {
                $msg .= " Stok produk telah dikembalikan secara otomatis ke inventaris.";
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
