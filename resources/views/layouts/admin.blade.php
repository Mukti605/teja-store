<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | Teja Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background font-sans text-foreground antialiased" x-data="{ sidebar: false }">
@php
    $nav = [
        ['admin.dashboard', 'Dashboard', 'admin.dashboard'],
        ['admin.orders.index', 'Riwayat transaksi', 'admin.orders.*'],
        ['admin.transactions.index', 'Tabel transaksi', 'admin.transactions.*'],
    ];
@endphp

<div class="lg:grid lg:grid-cols-[250px_1fr]">
    {{-- Overlay mobile --}}
    <div x-cloak x-show="sidebar" x-transition.opacity @click="sidebar = false" class="fixed inset-0 z-40 bg-deep/50 lg:hidden"></div>

    <aside :class="sidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-deep text-primary-foreground transition-transform duration-300 ease-out lg:sticky lg:top-0 lg:h-screen lg:w-auto">
        <div class="flex h-16 items-center gap-2.5 px-5 font-extrabold">
            <span class="grid size-9 place-items-center rounded-xl bg-accent text-accent-foreground">T</span> Teja Store
        </div>
        <nav class="flex-1 space-y-1 px-3 py-2 text-sm font-semibold">
            @foreach ($nav as [$route, $label, $pattern])
                <a href="{{ route($route) }}" class="flex items-center rounded-lg px-3 py-2.5 transition-colors {{ request()->routeIs($pattern) ? 'bg-primary text-primary-foreground' : 'text-primary-foreground/70 hover:bg-primary-foreground/10 hover:text-primary-foreground' }}">{{ $label }}</a>
            @endforeach
            <a href="{{ route('shop.index') }}" target="_blank" class="flex items-center rounded-lg px-3 py-2.5 text-primary-foreground/70 transition-colors hover:bg-primary-foreground/10 hover:text-primary-foreground">Lihat toko</a>
        </nav>
        <div class="border-t border-primary-foreground/15 p-4 text-sm">
            <p class="truncate font-semibold">{{ auth()->user()->name }}</p>
            <p class="truncate text-xs text-primary-foreground/60">{{ auth()->user()->email }}</p>
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                @csrf
                <x-ui.button type="submit" size="sm" class="w-full bg-primary-foreground/15 text-primary-foreground shadow-none hover:bg-primary-foreground/25">Keluar</x-ui.button>
            </form>
        </div>
    </aside>

    <div class="min-w-0">
        <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-border/70 bg-background/90 px-4 backdrop-blur sm:px-6">
            <button type="button" @click="sidebar = true" class="grid size-10 place-items-center rounded-lg bg-secondary lg:hidden" aria-label="Buka menu">
                <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
            <h1 class="text-lg font-extrabold">@yield('heading')</h1>
        </header>

        @foreach (['status' => 'bg-success/15 text-success', 'error' => 'bg-destructive/12 text-destructive'] as $key => $style)
            @if (session($key))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity class="px-4 pt-4 sm:px-6">
                    <div class="rounded-lg px-4 py-3 text-sm font-semibold {{ $style }}">{{ session($key) }}</div>
                </div>
            @endif
        @endforeach

        <main class="p-4 sm:p-6">@yield('content')</main>
    </div>
</div>
</body>
</html>
