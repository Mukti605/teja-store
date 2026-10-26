<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Cart $cart)
    {
        $lines = $cart->lines();

        return view('cart.index', [
            'lines' => $lines,
            'total' => $lines->sum('subtotal'),
        ]);
    }

    public function add(Request $request, Cart $cart)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);
        $added = $cart->add($product, (int) ($data['qty'] ?? 1));

        $message = match (true) {
            $added > 0 => "{$product->name} masuk ke keranjang.",
            $product->stock <= 0 => 'Stok produk ini sedang habis.',
            default => 'Jumlah di keranjang sudah sama dengan stok yang tersedia.',
        };

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => $added > 0,
                'count' => $cart->count(),
                'message' => $message,
            ], $added > 0 ? 200 : 422);
        }

        return back()->with($added > 0 ? 'status' : 'error', $message);
    }

    public function update(Request $request, Product $product, Cart $cart)
    {
        $qty = $request->validate(['qty' => ['required', 'integer', 'min:0', 'max:99']])['qty'];
        $cart->set($product, (int) $qty);

        return back();
    }

    public function remove(Product $product, Cart $cart)
    {
        $cart->remove($product);

        return back()->with('status', "{$product->name} dihapus dari keranjang.");
    }

    public function clear(Cart $cart)
    {
        $cart->clear();

        return back()->with('status', 'Keranjang dikosongkan.');
    }
}
