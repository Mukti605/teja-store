{{-- Catatan: pada komponen Blade, gunakan x-on:click / x-bind:disabled (bukan @click / :disabled). --}}
@props(['variant' => 'default', 'size' => 'default', 'href' => null, 'type' => 'button'])
@php
    $base = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-semibold transition-all duration-200 ease-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:pointer-events-none disabled:opacity-50 active:scale-[0.97]';
    $variants = [
        'default' => 'bg-primary text-primary-foreground shadow-sm hover:bg-primary/90 hover:shadow-md',
        'accent' => 'bg-accent text-accent-foreground shadow-sm hover:brightness-95 hover:shadow-md',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/70',
        'outline' => 'border border-border bg-card text-foreground hover:bg-secondary',
        'ghost' => 'text-foreground hover:bg-secondary',
        'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90',
    ];
    $sizes = [
        'sm' => 'h-8 px-3 text-xs',
        'default' => 'h-10 px-4',
        'lg' => 'h-12 px-6 text-base',
        'icon' => 'size-10',
    ];
    $classes = $base.' '.($variants[$variant] ?? $variants['default']).' '.($sizes[$size] ?? $sizes['default']);
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
