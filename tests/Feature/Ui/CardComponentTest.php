<?php

use Illuminate\Support\Facades\Blade;

// Named-slot markup is placed AFTER the default content. Blade captures slots by
// name regardless of position, and a leading named slot can leave PHPUnit flagging
// an unclosed output buffer during inline component rendering.

it('renders body content in a bordered card', function () {
    $html = Blade::render('<x-ui.card>Body here</x-ui.card>');

    expect($html)->toContain('Body here')
        ->toContain('rounded-lg')
        ->toContain('border-slate-200');
});

it('renders a header slot when provided', function () {
    $html = Blade::render('<x-ui.card>Body<x-slot:header>Title</x-slot:header></x-ui.card>');

    expect($html)->toContain('Title')
        ->toContain('border-b')
        ->toContain('Body');
});

it('renders a footer slot when provided', function () {
    $html = Blade::render('<x-ui.card>Body<x-slot:footer>Foot</x-slot:footer></x-ui.card>');

    expect($html)->toContain('Foot')->toContain('border-t');
});
