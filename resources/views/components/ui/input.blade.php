@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
])

@php
    $hasError = isset($errors) && $errors->has($name);
    $control = 'block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary '
        .($hasError ? 'border-danger' : 'border-slate-300');
@endphp

<div class="space-y-1">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $label }}</label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => $control]) }}
    />

    @if ($hasError)
        <p class="text-sm text-danger">{{ $errors->first($name) }}</p>
    @endif
</div>
