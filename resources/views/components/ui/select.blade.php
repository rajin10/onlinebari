@props([
    'name',
    'label' => null,
    'id' => null,
])

<div class="mb-4">
    @if ($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-slate-700 mb-1">{{ $label }}</label>
    @endif

    <select
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary']) }}
    >{{ $slot }}</select>
</div>
