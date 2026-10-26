@extends('layouts.admin')
@section('title', 'Riwayat transaksi')
@section('heading', 'Riwayat transaksi')

@section('content')
<x-ui.card class="p-4 sm:p-5">
    <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_180px_160px_160px_auto]">
        <x-ui.input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari invoice atau nama pelanggan" aria-label="Cari" />
        <select name="status" aria-label="Status" class="h-10 rounded-lg border border-border bg-card px-3 text-sm">
            <option value="">Semua status</option>
            @foreach ($statuses as $value => $label)<option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>@endforeach
        </select>
        <x-ui.input type="date" name="from" value="{{ $filters['from'] ?? '' }}" aria-label="Dari tanggal" />
        <x-ui.input type="date" name="to" value="{{ $filters['to'] ?? '' }}" aria-label="Sampai tanggal" />
        <div class="flex gap-2"><x-ui.button type="submit">Terapkan</x-ui.button><x-ui.button variant="outline" :href="route('admin.orders.index')">Reset</x-ui.button></div>
    </form>
</x-ui.card>

<x-ui.card class="mt-5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-left text-sm">
            <thead class="bg-muted text-xs text-muted-foreground">
                <tr><th class="px-4 py-3 font-semibold">Tanggal</th><th class="px-4 py-3 font-semibold">Invoice</th><th class="px-4 py-3 font-semibold">Pelanggan</th><th class="px-4 py-3 text-right font-semibold">Barang</th><th class="px-4 py-3 text-right font-semibold">Total</th><th class="px-4 py-3 font-semibold">Pembayaran</th><th class="px-4 py-3 font-semibold">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($orders as $o)
                    <tr class="transition-colors hover:bg-muted/60">
                        <td class="whitespace-nowrap px-4 py-3 text-muted-foreground">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3"><a class="font-bold text-primary hover:underline" href="{{ route('admin.orders.show', $o) }}">{{ $o->invoice_no }}</a></td>
                        <td class="px-4 py-3">{{ $o->customer_name }}</td>
                        <td class="px-4 py-3 text-right">{{ $o->qty_total }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right font-semibold">{{ \App\Support\Rupiah::format($o->total) }}</td>
                        <td class="px-4 py-3">{{ $o->payment_label }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$o->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-muted-foreground">Tidak ada transaksi yang cocok dengan filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-ui.card>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
