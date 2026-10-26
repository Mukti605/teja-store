@props(['variant' => 'default'])
@php
    $variants = [
        'default' => 'bg-primary/12 text-primary',
        'muted' => 'bg-muted text-muted-foreground',
        'laptop' => 'bg-laptop/12 text-laptop',
        'hp' => 'bg-hp/12 text-hp',
        'success' => 'bg-success/15 text-success',
        'warning' => 'bg-warning/15 text-warning',
        'destructive' => 'bg-destructive/12 text-destructive',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold '.($variants[$variant] ?? $variants['default'])]) }}>{{ $slot }}</span>
