<?php

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Blade;

/**
 * Build an in-memory product with manually-set relations so the component can be
 * rendered without touching the database.
 */
function luxCardProduct(array $ratings, array $colors): Product
{
    $product = new Product([
        'title' => 'Test Lamp',
        'slug' => 'test-lamp',
        'image' => json_encode(['x.jpg']),
        'regular_price' => 1000,
    ]);
    $product->id = 99;
    $product->setRelation('categories', collect([new Category(['name' => 'Table Lamps'])]));
    $product->setRelation('reviews', collect(array_map(fn ($r) => new Review(['rating' => $r]), $ratings)));
    $product->setRelation('colors', collect(array_map(fn ($c) => new Color(['name' => $c[0], 'code' => $c[1]]), $colors)));

    return $product;
}

it('renders the dynamic review count, average stars and colour swatches', function () {
    $product = luxCardProduct([4, 4, 5], [['Gold', '#FFCC00'], ['Black', '#000000']]);

    $html = Blade::render('<x-lux-product-card :product="$product" />', ['product' => $product]);

    expect($html)
        ->toContain('Table Lamps')
        ->toContain('Test Lamp')
        ->toContain('3 Reviews')   // dynamic count, pluralised
        ->toContain('★★★★☆')       // avg 4.33 -> 4 full stars
        ->toContain('#FFCC00')     // dynamic swatch colours from the pivot
        ->toContain('#000000');
});

it('uses an explicit category label when provided', function () {
    $product = luxCardProduct([5], [['Gold', '#FFCC00']]);

    $html = Blade::render('<x-lux-product-card :product="$product" category="Ceiling Lights" />', ['product' => $product]);

    expect($html)->toContain('Ceiling Lights');
});

it('shows "No reviews" and omits the swatch row when the product has neither', function () {
    $product = luxCardProduct([], []);

    $html = Blade::render('<x-lux-product-card :product="$product" />', ['product' => $product]);

    expect($html)
        ->toContain('No reviews')
        ->toContain('☆☆☆☆☆')             // zero average -> five empty stars
        ->not->toContain('lux-color-variants');
});
