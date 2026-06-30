@props(['variant' => 'info'])

@php
    $base = 'rounded-md border px-4 py-3 text-sm';

    $variants = [
        'success' => 'border-green-200 bg-green-50 text-green-800',
        'danger'  => 'border-red-200 bg-red-50 text-red-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'info'    => 'border-sky-200 bg-sky-50 text-sky-800',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['info']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</div>
