@props(['header' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-slate-200 bg-white shadow-sm']) }}>
    @isset($header)
        <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">{{ $header }}</div>
    @endisset

    <div class="p-4">{{ $slot }}</div>

    @isset($footer)
        <div class="border-t border-slate-200 px-4 py-3">{{ $footer }}</div>
    @endisset
</div>
