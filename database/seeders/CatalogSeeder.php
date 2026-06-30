<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Collection;
use Database\Seeders\Concerns\SeedsAssets;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    use SeedsAssets;

    public function run(): void
    {
        $brands = ['Cozy Lighting', 'Aurora Studio', 'Lumen & Co', 'Nimbus Decor', 'Halo Works', 'Ember Lab', 'Drift Living'];
        foreach ($brands as $name) {
            Brand::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => 1, 'cover_photo' => $this->pickImage('category')]
            );
        }

        $categories = [
            ['Table Lamps', true],
            ['Lamp Shades', true],
            ['Ceiling Lights', true],
            ['Wall Lights', true],
            ['Ambient Lighting', false],
            ['Bedside Lamps', true],
            ['Outdoor Lighting', false],
        ];
        foreach ($categories as $i => [$name, $onHome]) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'status' => 1,
                    'is_feature' => $i < 3 ? 1 : 0,
                    'pos' => $i + 1,
                    'cover_photo' => $this->pickImage('category'),
                    'is_shown_on_homepage' => $onHome ? 1 : 0,
                ]
            );
        }

        foreach (['New Arrivals', 'Best Sellers', 'Editor\'s Picks'] as $name) {
            Collection::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => 1, 'cover_photo' => $this->pickImage('collection')]
            );
        }
    }
}
