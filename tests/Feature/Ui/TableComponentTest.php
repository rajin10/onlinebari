<?php

use Illuminate\Support\Facades\Blade;

// Named-slot markup is placed AFTER the default content. Blade captures slots by
// name regardless of position, and a leading named slot can leave PHPUnit flagging
// an unclosed output buffer during inline component rendering.

it('wraps the table in an overflow-x-auto div', function () {
    $html = Blade::render('<x-ui.table><thead><tr><th>Name</th></tr></thead></x-ui.table>');

    expect($html)->toContain('overflow-x-auto')
        ->toContain('<table')
        ->toContain('</table>');
});

it('applies base table classes to the inner table element', function () {
    $html = Blade::render('<x-ui.table><tbody><tr><td>Cell</td></tr></tbody></x-ui.table>');

    expect($html)->toContain('w-full')
        ->toContain('text-left')
        ->toContain('text-sm')
        ->toContain('text-slate-700')
        ->toContain('border-slate-200');
});

it('applies th styling via Tailwind variant selectors', function () {
    $html = Blade::render('<x-ui.table><thead><tr><th>Col</th></tr></thead></x-ui.table>');

    // Blade HTML-escapes & -> &amp; in the class attribute, so assert on the
    // stable substring after the escaped ampersand.
    expect($html)
        ->toContain('_th]:border')
        ->toContain('_th]:bg-slate-50')
        ->toContain('_th]:px-3')
        ->toContain('_th]:font-medium');
});

it('applies td styling via Tailwind variant selectors', function () {
    $html = Blade::render('<x-ui.table><tbody><tr><td>Data</td></tr></tbody></x-ui.table>');

    expect($html)
        ->toContain('_td]:border')
        ->toContain('_td]:px-3')
        ->toContain('_td]:py-2');
});

it('applies even-row stripe class via Tailwind variant selector', function () {
    $html = Blade::render('<x-ui.table><tbody><tr><td>Row 1</td></tr><tr><td>Row 2</td></tr></tbody></x-ui.table>');

    expect($html)->toContain('nth-child(even)]:bg-slate-50');
});

it('renders thead and tbody slot content', function () {
    $html = Blade::render(
        '<x-ui.table>
            <thead><tr><th>Header</th></tr></thead>
            <tbody><tr><td>Row data</td></tr></tbody>
        </x-ui.table>'
    );

    expect($html)
        ->toContain('<thead>')
        ->toContain('<th>Header</th>')
        ->toContain('<tbody>')
        ->toContain('<td>Row data</td>');
});

it('merges extra attributes onto the inner table element', function () {
    $html = Blade::render('<x-ui.table id="my-table" data-page-length="25"><tbody></tbody></x-ui.table>');

    expect($html)
        ->toContain('id="my-table"')
        ->toContain('data-page-length="25"');
});

it('merges extra classes with the default table classes', function () {
    $html = Blade::render('<x-ui.table class="dataTable"><tbody></tbody></x-ui.table>');

    expect($html)
        ->toContain('dataTable')
        ->toContain('w-full');
});
