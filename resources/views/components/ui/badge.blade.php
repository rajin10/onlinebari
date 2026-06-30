@props(['variant' => 'neutral'])

@php
    $base = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium';

    $variants = [
        'neutral' => 'bg-slate-100 text-slate-700',
        'primary' => 'bg-primary/10 text-primary',
        'success' => 'bg-success/10 text-success',
        'danger'  => 'bg-danger/10 text-danger',
        'warning' => 'bg-warning/10 text-warning',
        'info'    => 'bg-info/10 text-info',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['neutral']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
