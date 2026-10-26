@extends('layouts.admin')
@section('title', 'Tabel transaksi')
@section('heading', 'Tabel transaksi')

@section('content')
@php $rp = fn ($v) => \App\Support\Rupiah::format($v); @endphp
<x-ui.card class="p-4 sm:p-5">
    <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-[1fr_150px_170px_150px_150px_auto]">
        <x-ui.input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari produk, invoice, pelanggan" aria-label="Cari" />
        <select name="category" aria-label="Kategori" class="h-10 rounded-lg border border-border bg-card px-3 text-sm">
            <option value="">Semua kategori</option>
            <option value="laptop" @selected(($filters['category'] ?? '') === 'laptop')>Laptop</option>
            <option value="hp" @selected(($filters['category'] ?? '') === 'hp')>HP</option>
        </select>
        <select name="status" aria-label="Status" class="h-10 rounded-lg border border-border bg-card px-3 text-sm">
            <option value="">Semua status</option>
            @foreach ($statuses as $value => $label)<option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>@endforeach
        </select>
        <x-ui.input type="date" name="from" value="{{ $filters['from'] ?? '' }}" aria-label="Dari tanggal" />
        <x-ui.input type="date" name="to" value="{{ $filters['to'] ?? '' }}" aria-label="Sampai tanggal" />
        <div class="flex gap-2"><x-ui.button type="submit">Terapkan</x-ui.button><x-ui.button variant="outline" :href="route('admin.transactions.index')">Reset</x-ui.button></div>
    </form>
</x-ui.card>

<div class="mt-5 flex flex-wrap items-center justify-between gap-3">
    <p class="text-sm text-muted-foreground"><span class="font-bold text-foreground">{{ $totalQty }}</span> unit &middot; total <span class="font-bold text-primary">{{ $rp($totalAmount) }}</span> sesuai filter</p>
    <div class="flex gap-2">
        <x-ui.button variant="outline" :href="route('admin.transactions.excel', $filters)">Export Excel</x-ui.button>
        <x-ui.button variant="accent" :href="route('admin.transactions.pdf', $filters)">Export PDF</x-ui.button>
    </div>
</div>

<x-ui.card class="mt-3 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[860px] text-left text-sm">
            <thead class="bg-muted text-xs text-muted-foreground">
                <tr><th class="px-4 py-3 font-semibold">Tanggal</th><th class="px-4 py-3 font-semibold">Invoice</th><th class="px-4 py-3 font-semibold">Pelanggan</th><th class="px-4 py-3 font-semibold">Produk</th><th class="px-4 py-3 font-semibold">Kategori</th><th class="px-4 py-3 text-right font-semibold">Harga</th><th class="px-4 py-3 text-right font-semibold">Qty</th><th class="px-4 py-3 text-right font-semibold">Subtotal</th><th class="px-4 py-3 font-semibold">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($items as $it)
                    <tr class="transition-colors hover:bg-muted/60">
                        <td class="whitespace-nowrap px-4 py-3 text-muted-foreground">{{ $it->order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3"><a class="font-bold text-primary hover:underline" href="{{ route('admin.orders.show', $it->order_id) }}">{{ $it->order->invoice_no }}</a></td>
                        <td class="px-4 py-3">{{ $it->order->customer_name }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $it->product_name }}</td>
                        <td class="px-4 py-3"><x-ui.badge :variant="$it->category === 'laptop' ? 'laptop' : 'hp'">{{ strtoupper($it->category) }}</x-ui.badge></td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">{{ $rp($it->price) }}</td>
                        <td class="px-4 py-3 text-right">{{ $it->qty }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right font-bold">{{ $rp($it->subtotal) }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$it->order->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-4 py-10 text-center text-muted-foreground">Tidak ada data yang cocok dengan filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-ui.card>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
