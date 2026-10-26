@extends('layouts.shop')
@section('title', 'Pesanan berhasil | Teja Store')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6">
    <div class="animate-rise text-center">
        <span class="mx-auto grid size-16 place-items-center rounded-full bg-success text-3xl text-white animate-pop">&#10003;</span>
        <h1 class="mt-5 text-3xl font-extrabold tracking-tight">Pesanan berhasil dibuat</h1>
        <p class="mt-2 text-muted-foreground">Simpan nomor invoice di bawah. Admin akan menghubungi {{ $order->customer_phone }} untuk konfirmasi pembayaran dan pengiriman.</p>
    </div>

    <x-ui.card class="mt-8 p-5 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div><p class="text-xs text-muted-foreground">Nomor invoice</p><p class="text-xl font-extrabold text-primary">{{ $order->invoice_no }}</p></div>
            <x-status-badge :status="$order->status" />
        </div>
        <ul class="mt-5 divide-y divide-border text-sm">
            @foreach ($order->items as $item)
                <li class="flex justify-between gap-3 py-2.5"><span>{{ $item->product_name }} <span class="text-muted-foreground">&times; {{ $item->qty }}</span></span><span class="font-bold">{{ \App\Support\Rupiah::format($item->subtotal) }}</span></li>
            @endforeach
        </ul>
        <div class="mt-2 flex justify-between border-t border-border pt-4 text-base"><span class="font-bold">Total</span><span class="font-extrabold text-primary">{{ \App\Support\Rupiah::format($order->total) }}</span></div>
        <dl class="mt-5 grid gap-3 rounded-lg bg-muted p-4 text-sm sm:grid-cols-2">
            <div><dt class="text-muted-foreground">Penerima</dt><dd class="font-semibold">{{ $order->customer_name }}</dd></div>
            <div><dt class="text-muted-foreground">Pembayaran</dt><dd class="font-semibold">{{ $order->payment_label }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-muted-foreground">Alamat</dt><dd class="font-semibold">{{ $order->customer_address }}</dd></div>
        </dl>
    </x-ui.card>

    <div class="mt-6 text-center"><x-ui.button :href="route('shop.index')" size="lg">Lanjut belanja</x-ui.button></div>
</div>
@endsection
