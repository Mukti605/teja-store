<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'category', 'price', 'qty', 'subtotal',
    ];

    protected function casts(): array
    {
        return ['price' => 'integer', 'qty' => 'integer', 'subtotal' => 'integer'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** Filter untuk halaman Tabel Transaksi (satu baris = satu produk dalam transaksi). */
    public function scopeFilter(Builder $query, array $f): Builder
    {
        return $query
            ->when(filled($f['category'] ?? null), fn ($q) => $q->where('category', $f['category']))
            ->when(filled($f['q'] ?? null), function ($q) use ($f) {
                $term = '%'.$f['q'].'%';
                $q->where(fn ($w) => $w->where('product_name', 'like', $term)
                    ->orWhereHas('order', fn ($o) => $o->where('invoice_no', 'like', $term)
                        ->orWhere('customer_name', 'like', $term)));
            })
            ->whereHas('order', fn ($o) => $o
                ->when(filled($f['status'] ?? null), fn ($x) => $x->where('status', $f['status']))
                ->when(filled($f['from'] ?? null), fn ($x) => $x->whereDate('created_at', '>=', $f['from']))
                ->when(filled($f['to'] ?? null), fn ($x) => $x->whereDate('created_at', '<=', $f['to'])));
    }
}
