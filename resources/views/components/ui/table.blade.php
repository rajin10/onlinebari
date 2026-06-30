<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full text-left text-sm text-slate-700 border border-slate-200 [&_th]:border [&_th]:border-slate-200 [&_th]:bg-slate-50 [&_th]:px-3 [&_th]:py-2 [&_th]:font-medium [&_td]:border [&_td]:border-slate-200 [&_td]:px-3 [&_td]:py-2 [&_tbody>tr:nth-child(even)]:bg-slate-50']) }}>
        {{ $slot }}
    </table>
</div>
