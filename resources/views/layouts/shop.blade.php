<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cart-add-url" content="{{ route('cart.add') }}">
    <title>@yield('title', 'Teja Store: Laptop dan HP')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php $cartCount = app(\App\Services\Cart::class)->count(); @endphp
<body class="min-h-screen bg-background font-sans text-foreground antialiased" x-data x-init="$store.cart.count = {{ $cartCount }}">

    <header class="sticky top-0 z-40 border-b border-border/70 bg-background/85 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('shop.index') }}" class="flex items-center gap-2.5 font-extrabold tracking-tight">
                <span class="grid size-9 place-items-center rounded-xl bg-primary text-primary-foreground shadow-sm">
                    <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M2 20h20"/></svg>
                </span>
                <span class="text-lg">Teja Store</span>
            </a>

            <nav class="hidden items-center gap-1 text-sm font-semibold sm:flex">
                <a href="{{ route('shop.index') }}#produk" class="rounded-lg px-3 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">Produk</a>
            </nav>

            <a href="{{ route('cart.index') }}" class="relative inline-flex h-10 items-center gap-2 rounded-lg bg-secondary px-4 text-sm font-semibold text-secondary-foreground transition-all hover:bg-secondary/70 active:scale-95" aria-label="Buka keranjang">
                <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a1 1 0 0 0 1 .8h9.2a1 1 0 0 0 1-.8L21 7H6"/></svg>
                Keranjang
                <span x-cloak x-show="$store.cart.count > 0" x-transition.scale
                      :class="$store.cart.bumped ? 'scale-125' : 'scale-100'"
                      class="grid min-w-5 place-items-center rounded-full bg-accent px-1.5 text-xs font-bold text-accent-foreground transition-transform duration-300"
                      x-text="$store.cart.count"></span>
            </a>
        </div>
    </header>

    @foreach (['status' => 'bg-success/15 text-success', 'error' => 'bg-destructive/12 text-destructive'] as $key => $style)
        @if (session($key))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity.duration.300ms
                 class="mx-auto mt-4 max-w-7xl px-4 sm:px-6">
                <div class="flex items-start justify-between gap-4 rounded-lg px-4 py-3 text-sm font-semibold {{ $style }}">
                    <span>{{ session($key) }}</span>
                    <button type="button" @click="show = false" class="opacity-70 hover:opacity-100" aria-label="Tutup">&times;</button>
                </div>
            </div>
        @endif
    @endforeach

    <main>@yield('content')</main>

    <footer class="mt-20 bg-deep text-primary-foreground/85">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-8 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="font-semibold">&copy; {{ date('Y') }} Teja Store. Laptop dan HP baru bergaransi.</p>
            <a href="{{ route('admin.login') }}" class="text-primary-foreground/60 transition-colors hover:text-primary-foreground">Masuk admin</a>
        </div>
    </footer>

    {{-- Toast: muncul saat produk masuk keranjang --}}
    <div x-cloak x-show="$store.cart.toast.show"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-4 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="translate-y-2 opacity-0"
         class="fixed inset-x-4 bottom-5 z-[60] mx-auto flex max-w-sm items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold shadow-lg sm:left-auto sm:right-6 sm:mx-0"
         :class="$store.cart.toast.type === 'ok' ? 'bg-deep text-primary-foreground' : 'bg-destructive text-destructive-foreground'"
         role="status">
        <span x-text="$store.cart.toast.message" class="flex-1"></span>
        <a x-show="$store.cart.toast.type === 'ok'" href="{{ route('cart.index') }}" class="rounded-md bg-accent px-2.5 py-1 text-xs font-bold text-accent-foreground">Lihat</a>
    </div>
</body>
</html>
