@extends('layouts.shop')
@section('title', 'Keranjang | Teja Store')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
    <h1 class="text-3xl font-extrabold tracking-tight">Keranjang belanja</h1>

    @if ($lines->isEmpty())
        <x-ui.card class="mt-6 p-10 text-center">
            <p class="text-lg font-bold">Keranjang masih kosong</p>
            <p class="mt-1 text-sm text-muted-foreground">Pilih laptop atau HP dulu, nanti muncul di sini.</p>
            <x-ui.button :href="route('shop.index').'#produk'" class="mt-5">Lihat produk</x-ui.button>
        </x-ui.card>
    @else
        <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="space-y-3">
                @foreach ($lines as $line)
                    @php $p = $line['product']; @endphp
                    <x-ui.card class="flex gap-4 p-3 sm:p-4">
                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="size-20 shrink-0 rounded-lg bg-muted object-contain p-1 sm:size-24">
                        <div class="flex min-w-0 flex-1 flex-col">
                            <p class="text-xs font-semibold text-muted-foreground">{{ $p->brand }}</p>
                            <h2 class="truncate font-bold">{{ $p->name }}</h2>
                            <p class="text-sm text-muted-foreground">{{ \App\Support\Rupiah::format($p->price) }} per unit</p>
                            <div class="mt-auto flex flex-wrap items-center justify-between gap-2 pt-2">
                                <form method="POST" action="{{ route('cart.update', $p) }}" class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <label for="qty-{{ $p->id }}" class="text-xs text-muted-foreground">Jumlah</label>
                                    <input id="qty-{{ $p->id }}" type="number" name="qty" min="1" max="{{ $p->stock }}" value="{{ $line['qty'] }}"
                                           @change="$el.form.requestSubmit()"
                                           class="h-9 w-16 rounded-lg border border-border bg-background px-2 text-center text-sm font-bold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                                    <span class="text-xs text-muted-foreground">maks. {{ $p->stock }}</span>
                                </form>
                                <p class="font-extrabold text-primary">{{ \App\Support\Rupiah::format($line['subtotal']) }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('cart.remove', $p) }}">
                            @csrf @method('DELETE')
                            <button class="grid size-8 place-items-center rounded-full text-muted-foreground transition-colors hover:bg-destructive/12 hover:text-destructive" aria-label="Hapus {{ $p->name }}">&times;</button>
                        </form>
                    </x-ui.card>
                @endforeach
            </div>

            <x-ui.card class="h-fit p-5 lg:sticky lg:top-24">
                <h2 class="text-lg font-extrabold">Ringkasan</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-muted-foreground">Jumlah barang</dt><dd class="font-semibold">{{ $lines->sum('qty') }}</dd></div>
                    <div class="flex justify-between border-t border-border pt-3 text-base"><dt class="font-bold">Total</dt><dd class="font-extrabold text-primary">{{ \App\Support\Rupiah::format($total) }}</dd></div>
                </dl>
                <x-ui.button variant="accent" size="lg" :href="route('checkout.show')" class="mt-5 w-full">Lanjut ke checkout</x-ui.button>
                <form method="POST" action="{{ route('cart.clear') }}" class="mt-2">
                    @csrf @method('DELETE')
                    <x-ui.button type="submit" variant="ghost" class="w-full text-muted-foreground">Kosongkan keranjang</x-ui.button>
                </form>
            </x-ui.card>
        </div>
    @endif
</div>
@endsection
