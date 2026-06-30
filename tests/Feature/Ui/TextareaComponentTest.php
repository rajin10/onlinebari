<?php

use Illuminate\Support\Facades\Blade;

it('renders a labelled textarea with the field name', function () {
    $html = Blade::render('<x-ui.textarea name="bio" label="Biography" />');

    expect($html)->toContain('name="bio"')
        ->toContain('Biography')
        ->toContain('rounded-md');
});

it('renders without a label when none is given', function () {
    $html = Blade::render('<x-ui.textarea name="notes" />');

    expect($html)->toContain('name="notes"')
        ->not->toContain('<label');
});

it('renders the default rows attribute', function () {
    $html = Blade::render('<x-ui.textarea name="bio" />');

    expect($html)->toContain('rows="4"');
});

it('accepts a custom rows value', function () {
    $html = Blade::render('<x-ui.textarea name="bio" rows="8" />');

    expect($html)->toContain('rows="8"');
});

it('renders slot content inside the textarea', function () {
    $html = Blade::render('<x-ui.textarea name="body">Hello world</x-ui.textarea>');

    expect($html)->toContain('Hello world');
});

it('wraps in a div with mb-4', function () {
    $html = Blade::render('<x-ui.textarea name="bio" />');

    expect($html)->toContain('mb-4');
});

it('merges extra attributes onto the textarea element', function () {
    $html = Blade::render('<x-ui.textarea name="bio" placeholder="Enter bio" />');

    expect($html)->toContain('placeholder="Enter bio"');
});
