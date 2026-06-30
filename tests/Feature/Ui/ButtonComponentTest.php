<?php

use Illuminate\Support\Facades\Blade;

it('renders a primary button with brand and slot content', function () {
    $html = Blade::render('<x-ui.button>Save</x-ui.button>');

    expect($html)->toContain('bg-primary')
        ->toContain('<button')
        ->toContain('Save');
});

it('renders the danger variant', function () {
    $html = Blade::render('<x-ui.button variant="danger">Delete</x-ui.button>');

    expect($html)->toContain('bg-danger');
});

it('renders as an anchor when href is provided', function () {
    $html = Blade::render('<x-ui.button href="/go">Go</x-ui.button>');

    expect($html)->toContain('<a')->toContain('href="/go"');
});

it('applies large size classes', function () {
    $html = Blade::render('<x-ui.button size="lg">Go</x-ui.button>');

    expect($html)->toContain('h-12');
});

it('merges caller-provided classes', function () {
    $html = Blade::render('<x-ui.button class="w-full">Go</x-ui.button>');

    expect($html)->toContain('w-full')->toContain('bg-primary');
});
