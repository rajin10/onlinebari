<?php

use Illuminate\Support\Facades\Blade;

// Named-slot markup is placed AFTER the default content. Blade captures slots by
// name regardless of position, and a leading named slot can leave PHPUnit flagging
// an unclosed output buffer during inline component rendering.

it('renders the modal root with Alpine x-data and open event listener', function () {
    $html = Blade::render('<x-ui.modal name="confirm">Body</x-ui.modal>');

    expect($html)
        ->toContain('x-data="{ open: false }"')
        ->toContain('x-show="open"')
        ->toContain('x-cloak')
        ->toContain('@open-modal-confirm.window="open = true"')
        ->toContain('@keydown.escape.window="open = false"');
});

it('renders fixed-positioning and centering classes on the root element', function () {
    $html = Blade::render('<x-ui.modal name="test">Body</x-ui.modal>');

    expect($html)
        ->toContain('fixed inset-0 z-50')
        ->toContain('flex items-center justify-center');
});

it('renders a backdrop div that closes the modal on click', function () {
    $html = Blade::render('<x-ui.modal name="test">Body</x-ui.modal>');

    expect($html)
        ->toContain('absolute inset-0 bg-black/50')
        ->toContain('@click="open = false"');
});

it('renders the panel with correct classes', function () {
    $html = Blade::render('<x-ui.modal name="test">Body</x-ui.modal>');

    expect($html)
        ->toContain('relative z-10')
        ->toContain('w-full max-w-lg')
        ->toContain('rounded-lg bg-white shadow-xl');
});

it('renders the title in the header', function () {
    $html = Blade::render('<x-ui.modal name="test" title="My Dialog">Body</x-ui.modal>');

    expect($html)
        ->toContain('My Dialog')
        ->toContain('border-b')
        ->toContain('flex items-center justify-between');
});

it('renders a close button with bx-x icon in the header', function () {
    $html = Blade::render('<x-ui.modal name="test" title="T">Body</x-ui.modal>');

    expect($html)
        ->toContain('bx bx-x')
        ->toContain('@click="open = false"');
});

it('renders body content in the slot', function () {
    $html = Blade::render('<x-ui.modal name="test">Hello body</x-ui.modal>');

    expect($html)
        ->toContain('Hello body')
        ->toContain('class="p-4"');
});

it('renders the footer slot when provided', function () {
    $html = Blade::render('<x-ui.modal name="test">Body<x-slot:footer>Save</x-slot:footer></x-ui.modal>');

    expect($html)
        ->toContain('Save')
        ->toContain('border-t');
});

it('omits the footer section when no footer slot is given', function () {
    $html = Blade::render('<x-ui.modal name="test">Body</x-ui.modal>');

    expect($html)->not->toContain('border-t');
});
