<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RuntimeException;

class CheckoutController extends Controller
{
    public function show(Cart $cart)
    {
        $lines = $cart->lines();

        if ($lines->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        return view('checkout.show', [
            'lines' => $lines,
            'total' => $lines->sum('subtotal'),
            'methods' => Order::PAYMENT_METHODS,
        ]);
    }

    public function store(Request $request, Cart $cart)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'customer_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', Rule::in(array_keys(Order::PAYMENT_METHODS))],
            'note' => ['nullable', 'string', 'max:255'],
        ], [
            'customer_name.required' => 'Nama penerima wajib diisi.',
            'customer_phone.required' => 'Nomor HP wajib diisi.',
            'customer_phone.regex' => 'Nomor HP hanya boleh berisi angka, spasi, tanda + atau -, dan panjangnya 9-20 karakter.',
            'customer_address.required' => 'Alamat pengiriman wajib diisi.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        $items = $cart->items();

        if (! $items) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        try {
            $order = DB::transaction(function () use ($data, $items) {
                // lockForUpdate mencegah dua pembeli mengambil stok terakhir secara bersamaan.
                $products = Product::whereIn('id', array_keys($items))->lockForUpdate()->get()->keyBy('id');

                $total = 0;
                $rows = [];

                foreach ($items as $id => $qty) {
                    $product = $products->get($id);

                    if (! $product || ! $product->is_active || $product->stock < $qty) {
                        throw new RuntimeException('Stok '.($product?->name ?? 'salah satu produk').' tidak mencukupi. Silakan periksa keranjang.');
                    }

                    $subtotal = $product->price * $qty;
                    $total += $subtotal;
                    $rows[] = [$product, $qty, $subtotal];
                }

                $order = Order::create([
                    ...$data,
                    'invoice_no' => Order::generateInvoiceNo(),
                    'status' => Order::STATUS_MENUNGGU,
                    'total' => $total,
                ]);

                foreach ($rows as [$product, $qty, $subtotal]) {
                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'category' => $product->category,
                        'price' => $product->price,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                    ]);
                    $product->decrement('stock', $qty);
                }

                return $order;
            });
        } catch (RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        $cart->clear();
        $request->session()->put('last_order_id', $order->id);

        return redirect()->route('checkout.success');
    }

    public function success(Request $request)
    {
        $order = Order::with('items')->find($request->session()->get('last_order_id'));

        if (! $order) {
            return redirect()->route('shop.index');
        }

        return view('checkout.success', ['order' => $order]);
    }
}
