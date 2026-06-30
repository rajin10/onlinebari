<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Collection;
use Database\Seeders\CatalogSeeder;

it('seeds branded lighting catalog', function () {
    $this->seed(CatalogSeeder::class);

    expect(Brand::count())->toBeGreaterThanOrEqual(6);
    expect(Category::count())->toBeGreaterThanOrEqual(6);
    expect(Collection::count())->toBeGreaterThanOrEqual(2);
    expect(Category::where('is_shown_on_homepage', true)->count())->toBeGreaterThanOrEqual(3);
    expect(Category::where('cover_photo', 'default.png')->count())->toBe(0);
});

it('CatalogSeeder is idempotent', function () {
    $this->seed(CatalogSeeder::class);
    $this->seed(CatalogSeeder::class);

    expect(Category::where('slug', 'table-lamps')->count())->toBe(1);
});
