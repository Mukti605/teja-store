@extends('layouts.admin')
@section('title', $order->invoice_no)
@section('heading', 'Detail transaksi')

@section('content')
<a href="{{ route('admin.orders.index') }}" class="mb-4 inline-block text-sm font-semibold text-primary hover:underline">&larr; Kembali ke riwayat</a>
<div class="grid gap-6 lg:grid-cols-[1fr_320px]">
    <x-ui.card class="p-5">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div><p class="text-xs text-muted-foreground">Invoice</p><p class="text-xl font-extrabold text-primary">{{ $order->invoice_no }}</p></div>
            <x-status-badge :status="$order->status" />
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[480px] text-left text-sm">
                <thead class="text-xs text-muted-foreground"><tr><th class="py-2 font-semibold">Produk</th><th class="py-2 text-right font-semibold">Harga</th><th class="py-2 text-right font-semibold">Qty</th><th class="py-2 text-right font-semibold">Subtotal</th></tr></thead>
                <tbody class="divide-y divide-border">
                    @foreach ($order->items as $item)
                        <tr><td class="py-2.5 font-semibold">{{ $item->product_name }} <x-ui.badge :variant="$item->category === 'laptop' ? 'laptop' : 'hp'" class="ml-1">{{ strtoupper($item->category) }}</x-ui.badge></td><td class="py-2.5 text-right">{{ \App\Support\Rupiah::format($item->price) }}</td><td class="py-2.5 text-right">{{ $item->qty }}</td><td class="py-2.5 text-right font-bold">{{ \App\Support\Rupiah::format($item->subtotal) }}</td></tr>
                    @endforeach
                </tbody>
                <tfoot><tr class="border-t border-border"><td colspan="3" class="pt-3 text-right font-bold">Total</td><td class="pt-3 text-right text-base font-extrabold text-primary">{{ \App\Support\Rupiah::format($order->total) }}</td></tr></tfoot>
            </table>
        </div>
    </x-ui.card>

    <div class="space-y-4">
        <x-ui.card class="p-5 text-sm">
            <h2 class="font-extrabold">Pelanggan</h2>
            <dl class="mt-3 space-y-2">
                <div><dt class="text-xs text-muted-foreground">Nama</dt><dd class="font-semibold">{{ $order->customer_name }}</dd></div>
                <div><dt class="text-xs text-muted-foreground">HP / WhatsApp</dt><dd class="font-semibold">{{ $order->customer_phone }}</dd></div>
                <div><dt class="text-xs text-muted-foreground">Alamat</dt><dd class="font-semibold">{{ $order->customer_address }}</dd></div>
                <div><dt class="text-xs text-muted-foreground">Pembayaran</dt><dd class="font-semibold">{{ $order->payment_label }}</dd></div>
                @if ($order->note)<div><dt class="text-xs text-muted-foreground">Catatan</dt><dd class="font-semibold">{{ $order->note }}</dd></div>@endif
                <div><dt class="text-xs text-muted-foreground">Dibuat</dt><dd class="font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</dd></div>
            </dl>
        </x-ui.card>

        <x-ui.card class="p-5">
            <h2 class="font-extrabold">Ubah status</h2>
            @if ($order->status === 'dibatalkan')
                <p class="mt-2 text-sm text-muted-foreground">Pesanan dibatalkan dan stok sudah dikembalikan. Status tidak bisa diubah lagi.</p>
            @else
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-3 space-y-3" x-data="{ s: '{{ $order->status }}' }">
                    @csrf @method('PATCH')
                    <select name="status" x-model="s" class="h-10 w-full rounded-lg border border-border bg-card px-3 text-sm">
                        @foreach ($statuses as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                    </select>
                    <p x-cloak x-show="s === 'dibatalkan'" x-transition class="text-xs text-destructive">Membatalkan pesanan akan mengembalikan stok dan tidak bisa diurungkan.</p>
                    <x-ui.button type="submit" class="w-full">Simpan status</x-ui.button>
                </form>
            @endif
        </x-ui.card>
    </div>
</div>
@endsection
