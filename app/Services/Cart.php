<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Keranjang berbasis session: [product_id => qty].
 * Pengunjung tidak perlu login untuk belanja; hanya admin yang punya akun.
 */
class Cart
{
    private const KEY = 'cart.items';

    /** @return array<int,int> */
    public function items(): array
    {
        return session(self::KEY, []);
    }

    public function count(): int
    {
        return array_sum($this->items());
    }

    /** @return int jumlah unit yang benar-benar ditambahkan (0 jika stok habis / sudah maksimal) */
    public function add(Product $product, int $qty = 1): int
    {
        $items = $this->items();
        $current = $items[$product->id] ?? 0;
        $new = min($current + max($qty, 1), $product->stock);

        if ($new <= $current) {
            return 0;
        }

        $items[$product->id] = $new;
        session([self::KEY => $items]);

        return $new - $current;
    }

    public function set(Product $product, int $qty): void
    {
        $items = $this->items();
        $qty = min(max($qty, 0), $product->stock);

        if ($qty <= 0) {
            unset($items[$product->id]);
        } else {
            $items[$product->id] = $qty;
        }

        session([self::KEY => $items]);
    }

    public function remove(Product $product): void
    {
        $this->set($product, 0);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    /** @return Collection<int, array{product: Product, qty: int, subtotal: int}> */
    public function lines(): Collection
    {
        $items = $this->items();

        if (! $items) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($items))->get()->keyBy('id');

        return collect($items)
            ->map(function (int $qty, int $id) use ($products) {
                $product = $products->get($id);
                if (! $product || ! $product->is_active) {
                    return null;
                }
                $qty = min($qty, $product->stock);
                if ($qty <= 0) {
                    return null;
                }

                return ['product' => $product, 'qty' => $qty, 'subtotal' => $product->price * $qty];
            })
            ->filter()
            ->values();
    }
}
