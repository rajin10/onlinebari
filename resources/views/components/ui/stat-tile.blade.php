@props([
    'variant' => 'info',
    'value' => '',
    'label' => '',
    'icon' => '',
    'href' => null,
])

@php
    $tiles = [
        'info'    => 'bg-tile-info',
        'warning' => 'bg-tile-warning',
        'success' => 'bg-tile-success',
        'primary' => 'bg-tile-primary',
        'danger'  => 'bg-tile-danger',
    ];
    $bg = $tiles[$variant] ?? $tiles['info'];

    // Drill-down: whole tile is clickable via a stretched-link overlay.
    // NOTE: the colored background MUST stay on this <div>, not on an <a>.
    // adminlte.css (Bootstrap reboot) ships an unlayered `a { background-color: transparent }`
    // which, per CSS cascade layers, overrides Tailwind's layered `bg-tile-*` utility and
    // would render the tile white. The overlay <a> is transparent, so it's unaffected.
    $isLink = ! empty($href);
    $interactive = $isLink ? 'transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md' : '';
@endphp

<div {{ $attributes->merge(['class' => "group relative overflow-hidden rounded-lg text-white shadow-sm $bg $interactive"]) }}>
    @if ($isLink)
        <a href="{{ $href }}" class="absolute inset-0 z-20" aria-label="{{ $label }} — view details"></a>
    @endif

    <div class="p-4">
        <h3 class="text-3xl font-bold leading-none">{{ $value }}</h3>
        <p class="mt-1 text-sm">{{ $label }}</p>
    </div>

    @if ($icon)
        <i class="{{ $icon }} pointer-events-none absolute right-3 top-3 text-5xl text-white/30 transition-transform duration-200 group-hover:scale-110"></i>
    @endif

    @if ($isLink)
        <span class="relative z-10 flex items-center justify-end gap-1 bg-black/10 px-4 py-1.5 text-xs font-medium text-white/90 transition-colors group-hover:bg-black/20">
            View details <i class="fas fa-arrow-right transition-transform duration-200 group-hover:translate-x-0.5"></i>
        </span>
    @endif
</div>
