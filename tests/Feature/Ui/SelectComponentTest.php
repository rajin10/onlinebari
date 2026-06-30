<?php

use Illuminate\Support\Facades\Blade;

it('renders a labelled select with the field name', function () {
    $html = Blade::render('<x-ui.select name="status" label="Status"><option value="1">Active</option></x-ui.select>');

    expect($html)
        ->toContain('name="status"')
        ->toContain('id="status"')
        ->toContain('Status')
        ->toContain('<label')
        ->toContain('rounded-md');
});

it('renders without a label when none is given', function () {
    $html = Blade::render('<x-ui.select name="role"><option value="admin">Admin</option></x-ui.select>');

    expect($html)
        ->toContain('name="role"')
        ->not->toContain('<label');
});

it('renders slot content as options', function () {
    $html = Blade::render('<x-ui.select name="color"><option value="red">Red</option><option value="blue">Blue</option></x-ui.select>');

    expect($html)
        ->toContain('<option value="red">Red</option>')
        ->toContain('<option value="blue">Blue</option>');
});

it('merges extra attributes onto the select element', function () {
    $html = Blade::render('<x-ui.select name="country" class="select2" data-placeholder="Pick one"><option value="bd">Bangladesh</option></x-ui.select>');

    expect($html)
        ->toContain('select2')
        ->toContain('data-placeholder="Pick one"');
});

it('wraps the select in a mb-4 div', function () {
    $html = Blade::render('<x-ui.select name="type"><option value="a">A</option></x-ui.select>');

    expect($html)->toContain('mb-4');
});
