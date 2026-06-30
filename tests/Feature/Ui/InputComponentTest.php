<?php

use Illuminate\Support\Facades\Blade;

it('renders a labelled input with the field name', function () {
    $html = Blade::render('<x-ui.input name="email" label="Email address" />');

    expect($html)->toContain('name="email"')
        ->toContain('Email address')
        ->toContain('rounded-md');
});

it('renders without a label when none is given', function () {
    $html = Blade::render('<x-ui.input name="phone" />');

    expect($html)->toContain('name="phone"')
        ->not->toContain('<label');
});

it('uses the provided default value', function () {
    $html = Blade::render('<x-ui.input name="city" value="Dhaka" />');

    expect($html)->toContain('value="Dhaka"');
});
