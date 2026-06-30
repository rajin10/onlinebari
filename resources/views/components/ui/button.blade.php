@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-md font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 disabled:opacity-50 disabled:pointer-events-none';

    $variants = [
        'primary'   => 'bg-primary text-white hover:bg-primary-600',
        'secondary' => 'bg-secondary text-white hover:bg-slate-700',
        'ghost'     => 'bg-transparent text-primary hover:bg-primary-50',
        'danger'    => 'bg-danger text-white hover:opacity-90',
        'success'   => 'bg-success text-white hover:opacity-90',
        'warning'   => 'bg-tile-warning text-black hover:opacity-90',
        'info'      => 'bg-tile-info text-white hover:opacity-90',
    ];

    $sizes = [
        'sm' => 'h-8 px-3 text-sm',
        'md' => 'h-10 px-4 text-sm',
        'lg' => 'h-12 px-6 text-base',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
