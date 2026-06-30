<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // if (app()->isProduction()) {
        //     $this->command?->warn('DemoDataSeeder skipped: not seeding demo data in production.');

        //     return;
        // }

        $this->call([
            CatalogSeeder::class,
            VendorSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            ContentSeeder::class,
        ]);
    }
}
