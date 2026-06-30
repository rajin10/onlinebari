@props(['tabs' => []])

<div x-data="{ tab: '{{ array_key_first($tabs) }}' }">

    {{-- Tab nav --}}
    <div class="flex gap-1 border-b border-slate-200">
        @foreach ($tabs as $key => $label)
            <button
                type="button"
                @click="tab='{{ $key }}'"
                :class="tab === '{{ $key }}'
                    ? 'border-b-2 border-primary text-primary'
                    : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 text-sm font-medium -mb-px"
            >{{ $label }}</button>
        @endforeach
    </div>

    {{-- Tab panels --}}
    @foreach ($tabs as $key => $label)
        <div x-show="tab === '{{ $key }}'" x-cloak class="py-4">
            {{ $$key ?? '' }}
        </div>
    @endforeach

</div>
