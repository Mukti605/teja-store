<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUSES = [
        'menunggu' => 'Menunggu pembayaran',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    public const PAYMENT_METHODS = [
        'transfer' => 'Transfer bank',
        'qris' => 'QRIS',
        'cod' => 'Bayar di tempat (COD)',
    ];

    protected $fillable = [
        'invoice_no', 'customer_name', 'customer_phone', 'customer_address',
        'payment_method', 'note', 'status', 'total',
    ];

    protected function casts(): array
    {
        return ['total' => 'integer'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPaymentLabelAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public static function generateInvoiceNo(): string
    {
        do {
            $no = 'TJS-'.now()->format('ymd').'-'.random_int(1000, 9999);
        } while (static::where('invoice_no', $no)->exists());

        return $no;
    }

    /** Filter untuk halaman Riwayat Transaksi. */
    public function scopeFilter(Builder $query, array $f): Builder
    {
        return $query
            ->when(filled($f['q'] ?? null), function ($q) use ($f) {
                $term = '%'.$f['q'].'%';
                $q->where(fn ($w) => $w->where('invoice_no', 'like', $term)
                    ->orWhere('customer_name', 'like', $term));
            })
            ->when(filled($f['status'] ?? null), fn ($q) => $q->where('status', $f['status']))
            ->when(filled($f['from'] ?? null), fn ($q) => $q->whereDate('created_at', '>=', $f['from']))
            ->when(filled($f['to'] ?? null), fn ($q) => $q->whereDate('created_at', '<=', $f['to']));
    }
}
