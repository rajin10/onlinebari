@props([
    'name',
    'label' => null,
    'rows' => 4,
])

@php
    $hasError = isset($errors) && $errors->has($name);
    $control = 'block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary '
        .($hasError ? 'border-danger' : 'border-slate-300');
@endphp

<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge(['class' => $control]) }}
    >{{ $slot }}</textarea>

    @if ($hasError)
        <p class="text-sm text-danger">{{ $errors->first($name) }}</p>
    @endif
</div>
