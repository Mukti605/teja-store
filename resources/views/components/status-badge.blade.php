@props(['status'])
@php
    $map = [
        'menunggu' => 'warning',
        'diproses' => 'laptop',
        'dikirim' => 'default',
        'selesai' => 'success',
        'dibatalkan' => 'destructive',
    ];
@endphp
<x-ui.badge :variant="$map[$status] ?? 'muted'">{{ \App\Models\Order::STATUSES[$status] ?? $status }}</x-ui.badge>
