@extends('layouts.shop')
@section('title', 'Checkout | Teja Store')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
    <h1 class="text-3xl font-extrabold tracking-tight">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}" class="mt-6 grid gap-6 lg:grid-cols-[1fr_340px]">
        @csrf
        <x-ui.card class="space-y-5 p-5 sm:p-6">
            <h2 class="text-lg font-extrabold">Data penerima</h2>

            <div>
                <label for="customer_name" class="mb-1.5 block text-sm font-semibold">Nama penerima</label>
                <x-ui.input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" autocomplete="name" required />
                @error('customer_name')<p class="mt-1 text-sm text-destructive">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="customer_phone" class="mb-1.5 block text-sm font-semibold">Nomor HP / WhatsApp</label>
                <x-ui.input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" inputmode="tel" autocomplete="tel" placeholder="08xxxxxxxxxx" required />
                @error('customer_phone')<p class="mt-1 text-sm text-destructive">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="customer_address" class="mb-1.5 block text-sm font-semibold">Alamat pengiriman</label>
                <textarea id="customer_address" name="customer_address" rows="3" required class="w-full rounded-lg border border-border bg-card px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">{{ old('customer_address') }}</textarea>
                @error('customer_address')<p class="mt-1 text-sm text-destructive">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="note" class="mb-1.5 block text-sm font-semibold">Catatan (opsional)</label>
                <x-ui.input id="note" name="note" value="{{ old('note') }}" />
            </div>

            <fieldset>
                <legend class="mb-2 text-sm font-semibold">Metode pembayaran</legend>
                <div class="grid gap-2 sm:grid-cols-3">
                    @foreach ($methods as $value => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $value }}" class="peer sr-only" @checked(old('payment_method', 'transfer') === $value)>
                            <span class="block rounded-lg border border-border bg-background p-3 text-center text-sm font-semibold transition-all duration-200 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary peer-focus-visible:ring-2 peer-focus-visible:ring-ring">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('payment_method')<p class="mt-1 text-sm text-destructive">{{ $message }}</p>@enderror
            </fieldset>
        </x-ui.card>

        <x-ui.card class="h-fit p-5 lg:sticky lg:top-24">
            <h2 class="text-lg font-extrabold">Pesanan kamu</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @foreach ($lines as $line)
                    <li class="flex justify-between gap-3">
                        <span class="min-w-0"><span class="block truncate font-semibold">{{ $line['product']->name }}</span><span class="text-muted-foreground">{{ $line['qty'] }} &times; {{ \App\Support\Rupiah::format($line['product']->price) }}</span></span>
                        <span class="shrink-0 font-bold">{{ \App\Support\Rupiah::format($line['subtotal']) }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 flex justify-between border-t border-border pt-4 text-base"><span class="font-bold">Total</span><span class="font-extrabold text-primary">{{ \App\Support\Rupiah::format($total) }}</span></div>
            <x-ui.button type="submit" variant="accent" size="lg" class="mt-5 w-full">Buat pesanan</x-ui.button>
            <a href="{{ route('cart.index') }}" class="mt-3 block text-center text-sm font-semibold text-muted-foreground hover:text-foreground">Kembali ke keranjang</a>
        </x-ui.card>
    </form>
</div>
@endsection
