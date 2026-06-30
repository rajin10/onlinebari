<?php

use Illuminate\Support\Facades\Blade;

// Named-slot markup is placed AFTER the default content. Blade captures slots by
// name regardless of position, and a leading named slot can leave PHPUnit flagging
// an unclosed output buffer during inline component rendering.

it('renders tab buttons for each entry in the tabs array', function () {
    $html = Blade::render(
        '<x-ui.tabs :tabs="$tabs"></x-ui.tabs>',
        ['tabs' => ['overview' => 'Overview', 'details' => 'Details']],
    );

    expect($html)
        ->toContain('Overview')
        ->toContain('Details')
        ->toContain("tab='overview'")
        ->toContain("tab='details'");
});

it('marks the first tab active by default via x-data initialisation', function () {
    $html = Blade::render(
        '<x-ui.tabs :tabs="$tabs"></x-ui.tabs>',
        ['tabs' => ['alpha' => 'Alpha', 'beta' => 'Beta']],
    );

    expect($html)->toContain("tab: 'alpha'");
});

it('renders a panel div for each tab with x-show and x-cloak', function () {
    $html = Blade::render(
        '<x-ui.tabs :tabs="$tabs"></x-ui.tabs>',
        ['tabs' => ['foo' => 'Foo', 'bar' => 'Bar']],
    );

    expect($html)
        ->toContain("tab === 'foo'")
        ->toContain("tab === 'bar'")
        ->toContain('x-cloak')
        ->toContain('py-4');
});

it('renders named slot content inside the matching panel', function () {
    $html = Blade::render(
        '<x-ui.tabs :tabs="$tabs">Panel A content<x-slot:a>Panel A content</x-slot:a><x-slot:b>Panel B content</x-slot:b></x-ui.tabs>',
        ['tabs' => ['a' => 'Tab A', 'b' => 'Tab B']],
    );

    expect($html)
        ->toContain('Panel A content')
        ->toContain('Panel B content');
});

it('renders nav buttons with base styling classes', function () {
    $html = Blade::render(
        '<x-ui.tabs :tabs="$tabs"></x-ui.tabs>',
        ['tabs' => ['x' => 'X']],
    );

    expect($html)
        ->toContain('px-4')
        ->toContain('py-2')
        ->toContain('text-sm')
        ->toContain('font-medium')
        ->toContain('-mb-px');
});

it('renders a bottom-border nav container', function () {
    $html = Blade::render(
        '<x-ui.tabs :tabs="$tabs"></x-ui.tabs>',
        ['tabs' => ['x' => 'X']],
    );

    expect($html)
        ->toContain('border-b')
        ->toContain('border-slate-200')
        ->toContain('flex');
});

it('applies active border-primary class binding for the active tab', function () {
    $html = Blade::render(
        '<x-ui.tabs :tabs="$tabs"></x-ui.tabs>',
        ['tabs' => ['home' => 'Home', 'away' => 'Away']],
    );

    expect($html)
        ->toContain('border-primary')
        ->toContain('text-primary')
        ->toContain('text-slate-500');
});
