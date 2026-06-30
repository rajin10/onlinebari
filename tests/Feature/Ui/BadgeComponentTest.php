<?php

use Illuminate\Support\Facades\Blade;

it('renders a neutral badge by default', function () {
    $html = Blade::render('<x-ui.badge>New</x-ui.badge>');

    expect($html)->toContain('New')
        ->toContain('rounded-full')
        ->toContain('bg-slate-100');
});

it('renders the success variant with status token', function () {
    $html = Blade::render('<x-ui.badge variant="success">Paid</x-ui.badge>');

    expect($html)->toContain('text-success');
});

it('renders the danger variant with status token', function () {
    $html = Blade::render('<x-ui.badge variant="danger">Failed</x-ui.badge>');

    expect($html)->toContain('text-danger');
});
