@props(['name', 'title' => null, 'size' => 'lg'])

<div
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    @open-modal-{{ $name }}.window="open = true"
    @close-modal-{{ $name }}.window="open = false"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

    {{-- Panel --}}
    @php $sizeMap = ['sm' => 'max-w-sm', 'md' => 'max-w-md', 'lg' => 'max-w-lg', 'xl' => 'max-w-3xl', '2xl' => 'max-w-5xl']; @endphp
    <div class="relative z-10 w-full {{ $sizeMap[$size] ?? 'max-w-lg' }} rounded-lg bg-white shadow-xl">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b px-4 py-3">
            <h3 class="font-medium">{{ $title }}</h3>
            <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600">
                <i class="bx bx-x text-xl"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-4">{{ $slot }}</div>

        {{-- Footer --}}
        @if (isset($footer))
            <div class="border-t px-4 py-3">{{ $footer }}</div>
        @endif

    </div>
</div>
