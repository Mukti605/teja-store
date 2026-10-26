@extends('layouts.admin')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
@php $rp = fn ($v) => \App\Support\Rupiah::format($v); @endphp
<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['Pendapatan', $rp($revenue), 'Tidak termasuk pesanan dibatalkan', 'text-primary'],
            ['Total pesanan', $ordersCount, $pendingCount.' menunggu pembayaran', 'text-laptop'],
            ['Barang terjual', $itemsSold, 'Unit dari semua pesanan aktif', 'text-success'],
            ['Produk aktif', $productsCount, $lowStock->count().' produk stok menipis', 'text-hp'],
        ] as [$label, $value, $hint, $color])
            <x-ui.card class="p-5">
                <p class="text-sm font-semibold text-muted-foreground">{{ $label }}</p>
                <p class="mt-1 text-2xl font-extrabold {{ $color }}">{{ $value }}</p>
                <p class="mt-1 text-xs text-muted-foreground">{{ $hint }}</p>
            </x-ui.card>
        @endforeach
    </div>

    <x-ui.card class="p-5">
        <h2 class="font-extrabold">Penjualan 14 hari terakhir</h2>
        <div class="mt-4 h-72" x-data="salesChart(@js($chart))"><canvas x-ref="canvas"></canvas></div>
    </x-ui.card>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.card class="p-5">
            <h2 class="font-extrabold">Produk terlaris</h2>
            <ul class="mt-3 divide-y divide-border text-sm">
                @forelse ($topProducts as $row)
                    <li class="flex items-center justify-between gap-3 py-2.5"><span class="truncate font-semibold">{{ $row->product_name }}</span><span class="shrink-0 text-muted-foreground">{{ $row->qty_total }} unit &middot; {{ $rp($row->revenue) }}</span></li>
                @empty
                    <li class="py-6 text-center text-muted-foreground">Belum ada transaksi.</li>
                @endforelse
            </ul>
            @php $sumCat = max(1, $byCategory->sum()); @endphp
            <div class="mt-4 space-y-3 border-t border-border pt-4 text-sm">
                @foreach (['laptop' => ['Laptop', 'bg-laptop'], 'hp' => ['HP', 'bg-hp']] as $key => [$label, $bar])
                    @php $val = (int) ($byCategory[$key] ?? 0); @endphp
                    <div>
                        <div class="mb-1 flex justify-between"><span class="font-semibold">{{ $label }}</span><span class="text-muted-foreground">{{ $rp($val) }}</span></div>
                        <div class="h-2 overflow-hidden rounded-full bg-muted"><div class="{{ $bar }} h-full rounded-full transition-all duration-700" style="width: {{ round($val / $sumCat * 100) }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card class="p-5">
            <h2 class="font-extrabold">Stok menipis (5 unit atau kurang)</h2>
            <ul class="mt-3 divide-y divide-border text-sm">
                @forelse ($lowStock as $p)
                    <li class="flex items-center justify-between gap-3 py-2.5"><span class="truncate font-semibold">{{ $p->name }}</span><x-ui.badge :variant="$p->stock === 0 ? 'destructive' : 'warning'">{{ $p->stock === 0 ? 'Habis' : 'Sisa '.$p->stock }}</x-ui.badge></li>
                @empty
                    <li class="py-6 text-center text-muted-foreground">Semua stok aman.</li>
                @endforelse
            </ul>
        </x-ui.card>
    </div>

    <x-ui.card class="p-5">
        <div class="flex items-center justify-between"><h2 class="font-extrabold">Pesanan terbaru</h2><a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-primary hover:underline">Lihat semua</a></div>
        <div class="mt-3 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="text-xs text-muted-foreground"><tr><th class="py-2 pr-3 font-semibold">Invoice</th><th class="py-2 pr-3 font-semibold">Pelanggan</th><th class="py-2 pr-3 font-semibold">Total</th><th class="py-2 font-semibold">Status</th></tr></thead>
                <tbody class="divide-y divide-border">
                    @forelse ($recent as $o)
                        <tr class="transition-colors hover:bg-muted/60"><td class="py-2.5 pr-3"><a class="font-bold text-primary hover:underline" href="{{ route('admin.orders.show', $o) }}">{{ $o->invoice_no }}</a></td><td class="py-2.5 pr-3">{{ $o->customer_name }}</td><td class="py-2.5 pr-3 font-semibold">{{ $rp($o->total) }}</td><td class="py-2.5"><x-status-badge :status="$o->status" /></td></tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-muted-foreground">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
</div>
@endsection
