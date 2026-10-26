<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tabel Transaksi Teja Store</title>
    <style>
        /* DejaVu Sans bawaan Dompdf: aman untuk karakter Rp dan tanda baca. */
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #12343b; }
        h1 { font-size: 16px; margin: 0 0 2px; color: #0b7a8a; }
        .meta { color: #4f7580; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0b7a8a; color: #f1fbfc; text-align: left; padding: 6px 5px; font-size: 8.5px; }
        td { padding: 5px; border-bottom: 1px solid #bcd8dd; vertical-align: top; }
        tr:nth-child(even) td { background: #eef7f7; }
        .r { text-align: right; }
        tfoot td { font-weight: bold; border-top: 2px solid #0b7a8a; background: #d3e9ed; }
    </style>
</head>
<body>
    <h1>Tabel Transaksi Teja Store</h1>
    <div class="meta">
        Dicetak {{ $printedAt->format('d/m/Y H:i') }}
        @php
            $label = collect([
                'Pencarian' => $filters['q'] ?? null,
                'Kategori' => ! empty($filters['category']) ? strtoupper($filters['category']) : null,
                'Status' => ! empty($filters['status']) ? (\App\Models\Order::STATUSES[$filters['status']] ?? $filters['status']) : null,
                'Dari' => $filters['from'] ?? null,
                'Sampai' => $filters['to'] ?? null,
            ])->filter()->map(fn ($v, $k) => "$k: $v")->implode(', ');
        @endphp
        @if ($label) &mdash; Filter: {{ $label }} @endif
    </div>

    <table>
        <thead>
            <tr><th>Tanggal</th><th>Invoice</th><th>Pelanggan</th><th>Produk</th><th>Kategori</th><th class="r">Harga</th><th class="r">Qty</th><th class="r">Subtotal</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse ($items as $it)
                <tr>
                    <td>{{ $it->order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $it->order->invoice_no }}</td>
                    <td>{{ $it->order->customer_name }}</td>
                    <td>{{ $it->product_name }}</td>
                    <td>{{ strtoupper($it->category) }}</td>
                    <td class="r">{{ \App\Support\Rupiah::format($it->price) }}</td>
                    <td class="r">{{ $it->qty }}</td>
                    <td class="r">{{ \App\Support\Rupiah::format($it->subtotal) }}</td>
                    <td>{{ \App\Models\Order::STATUSES[$it->order->status] ?? $it->order->status }}</td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center;padding:14px">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr><td colspan="6" class="r">Total</td><td class="r">{{ $totalQty }}</td><td class="r">{{ \App\Support\Rupiah::format($totalAmount) }}</td><td></td></tr>
        </tfoot>
    </table>
</body>
</html>
