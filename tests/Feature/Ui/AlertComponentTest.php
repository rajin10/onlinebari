<?php

use Illuminate\Support\Facades\Blade;

it('renders the info variant by default', function () {
    $html = Blade::render('<x-ui.alert>Something happened</x-ui.alert>');

    expect($html)->toContain('Something happened')
        ->toContain('rounded-md')
        ->toContain('border-sky-200')
        ->toContain('bg-sky-50')
        ->toContain('text-sky-800');
});

it('renders the success variant', function () {
    $html = Blade::render('<x-ui.alert variant="success">All good</x-ui.alert>');

    expect($html)->toContain('border-green-200')
        ->toContain('bg-green-50')
        ->toContain('text-green-800');
});

it('renders the danger variant', function () {
    $html = Blade::render('<x-ui.alert variant="danger">Error occurred</x-ui.alert>');

    expect($html)->toContain('border-red-200')
        ->toContain('bg-red-50')
        ->toContain('text-red-800');
});

it('renders the warning variant', function () {
    $html = Blade::render('<x-ui.alert variant="warning">Watch out</x-ui.alert>');

    expect($html)->toContain('border-amber-200')
        ->toContain('bg-amber-50')
        ->toContain('text-amber-800');
});

it('renders as a div element', function () {
    $html = Blade::render('<x-ui.alert>Test</x-ui.alert>');

    expect($html)->toContain('<div')
        ->toContain('</div>');
});

it('merges extra attributes onto the div', function () {
    $html = Blade::render('<x-ui.alert id="my-alert">Test</x-ui.alert>');

    expect($html)->toContain('id="my-alert"');
});
