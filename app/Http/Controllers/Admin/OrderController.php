<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/** Riwayat Transaksi: satu baris = satu pesanan. */
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'status', 'from', 'to']);

        $orders = Order::query()
            ->withCount('items')
            ->withSum('items as qty_total', 'qty')
            ->filter($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'filters' => $filters,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load('items'),
            'statuses' => Order::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $status = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
        ])['status'];

        if ($order->status === Order::STATUS_DIBATALKAN) {
            return back()->with('error', 'Pesanan yang sudah dibatalkan tidak bisa diubah lagi.');
        }

        DB::transaction(function () use ($order, $status) {
            // Pesanan dibatalkan: kembalikan stok ke produk.
            if ($status === Order::STATUS_DIBATALKAN) {
                foreach ($order->items()->whereNotNull('product_id')->get() as $item) {
                    $item->product?->increment('stock', $item->qty);
                }
            }
            $order->update(['status' => $status]);
        });

        return back()->with('status', "Status {$order->invoice_no} diubah menjadi ".Order::STATUSES[$status].'.');
    }
}
