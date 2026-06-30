# Demo Data Seeders Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Seed a coherent, full demo dataset (decorative-lighting e-commerce) so a freshly-migrated local app's storefront, admin, and vendor dashboards all look populated.

**Architecture:** A production-guarded `DemoDataSeeder` orchestrates per-domain seeders (catalog, vendors, customers, products, orders, content). Volume entities use Laravel factories + Faker; fixed sets are curated. All images reuse files already committed under `public/uploads/*`, so nothing renders broken. Essential seeders (`RoleSeeder`, `AdminSeeder`, `SettingSeeder`) are idempotent and run in every environment.

**Tech Stack:** Laravel 11, PHP 8.2+, SQLite (local + in-memory test DB), Pest, Faker, Laravel Scout (algolia driver — bypassed during seeding).

## Global Constraints

- **Niche:** decorative / 3D-printed home lighting (table lamps, lamp shades, ambient lights). Product/category/brand names must fit this niche.
- **Currency:** Bangladeshi Taka — symbol `৳`, code `BDT`. Prices are integers ~1200–9000.
- **Images:** reuse only files already in `public/uploads/{product,category,slider,banner,collection}`. Never hardcode a single hash filename — list the directory at runtime. Fallback `'default.png'`.
- **Idempotency:** every seeder is safe to re-run. Use `firstOrCreate`/`updateOrCreate` for fixed sets; guard volume seeders by a count check.
- **Production guard:** demo data must never seed when `app()->isProduction()`.
- **Scout:** `Product` is `Searchable` with the `algolia` driver. Wrap all product creation in `Product::withoutSyncingToSearch(...)`. Tests set `SCOUT_DRIVER=null`.
- **Mass assignment:** most models are `$guarded = ['id']`; `Banner`, `Order`, `OrderDetails` use `$fillable` (use only listed keys).
- **Convention:** match surrounding code style; create no migrations (schema already exists).

---

## File Structure

```
database/seeders/
  DatabaseSeeder.php          # MODIFY: call SettingSeeder + DemoDataSeeder
  RoleSeeder.php              # MODIFY: firstOrCreate (idempotent)
  AdminSeeder.php             # (unchanged — already updateOrCreate)
  SettingSeeder.php           # NEW: site settings incl. logo/currency/header
  DemoDataSeeder.php          # NEW: prod-guard + orchestrates domain seeders
  CatalogSeeder.php           # NEW: brands, categories, collections
  VendorSeeder.php            # NEW: vendor users + ShopInfo + VendorAccount
  CustomerSeeder.php          # NEW: ~25 customers
  ProductSeeder.php           # NEW: ~45 products + images + pivots + reviews
  OrderSeeder.php             # NEW: ~30 orders + order_details
  ContentSeeder.php           # NEW: sliders, banners, coupons, blogs
  Concerns/SeedsAssets.php    # NEW: trait — list/pick committed upload images
database/factories/
  UserFactory.php             # NEW
  ProductFactory.php          # NEW
  ReviewFactory.php           # NEW
  OrderFactory.php            # NEW
tests/Feature/Seeders/
  *SeederTest.php             # NEW: one per task
phpunit.xml                   # MODIFY: add SCOUT_DRIVER=null
```

---

## Task 1: Idempotent essentials + asset helper + settings

**Files:**
- Modify: `phpunit.xml` (add Scout env)
- Modify: `database/seeders/RoleSeeder.php`
- Create: `database/seeders/Concerns/SeedsAssets.php`
- Create: `database/seeders/SettingSeeder.php`
- Test: `tests/Feature/Seeders/SettingSeederTest.php`

**Interfaces:**
- Produces: trait `Database\Seeders\Concerns\SeedsAssets` with `protected function imagePool(string $dir): array` (filenames in `public/uploads/$dir`) and `protected function pickImage(string $dir, string $fallback = 'default.png'): string`.
- Produces: `RoleSeeder` (idempotent, roles `admin`/`vendor`/`user`), `SettingSeeder`.

- [ ] **Step 0: Ensure dependencies installed (worktree has no `vendor/`)**

Run: `composer install`
Expected: vendor/ present; `php artisan --version` prints Laravel 11.

- [ ] **Step 1: Add Scout null driver to phpunit.xml**

In `phpunit.xml`, inside `<php>`, add alongside the other `<env>` lines:

```xml
<env name="SCOUT_DRIVER" value="null"/>
```

- [ ] **Step 2: Write the failing test**

Create `tests/Feature/Seeders/SettingSeederTest.php`:

```php
<?php

use App\Models\Setting;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SettingSeeder;

it('seeds logo and currency settings', function () {
    $this->seed(SettingSeeder::class);

    expect(Setting::where('name', 'logo')->value('value'))->toBe('logo.svg');
    expect(Setting::where('name', 'CURRENCY_CODE_MIN')->value('value'))->not->toBeNull();
    expect(Setting::where('name', 'TOP_HEADER_STYLE')->value('value'))->toBe('1');
});

it('SettingSeeder is idempotent', function () {
    $this->seed(SettingSeeder::class);
    $this->seed(SettingSeeder::class);

    expect(Setting::where('name', 'logo')->count())->toBe(1);
});

it('RoleSeeder is idempotent', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(RoleSeeder::class);

    expect(\App\Models\Role::count())->toBe(3);
});
```

- [ ] **Step 3: Run test to verify it fails**

Run: `php artisan test tests/Feature/Seeders/SettingSeederTest.php`
Expected: FAIL — `Class "Database\Seeders\SettingSeeder" not found` and roles count 6 (duplicate) before the RoleSeeder fix.

- [ ] **Step 4: Make RoleSeeder idempotent**

Replace the body of `database/seeders/RoleSeeder.php`'s `run()`:

```php
public function run(): void
{
    foreach ([['Admin', 'admin'], ['Vendor', 'vendor'], ['User', 'user']] as [$name, $slug]) {
        Role::firstOrCreate(['slug' => $slug], ['name' => $name]);
    }
}
```

- [ ] **Step 5: Create the SeedsAssets trait**

Create `database/seeders/Concerns/SeedsAssets.php`:

```php
<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\File;

trait SeedsAssets
{
    /** Filenames present in public/uploads/$dir (no path). */
    protected function imagePool(string $dir): array
    {
        $path = public_path('uploads/' . $dir);

        if (! File::isDirectory($path)) {
            return [];
        }

        return collect(File::files($path))
            ->map(fn ($f) => $f->getFilename())
            ->values()
            ->all();
    }

    /** A real filename from the pool, or the fallback when the dir is empty. */
    protected function pickImage(string $dir, string $fallback = 'default.png'): string
    {
        $pool = $this->imagePool($dir);

        return $pool === [] ? $fallback : $pool[array_rand($pool)];
    }
}
```

- [ ] **Step 6: Create SettingSeeder**

Create `database/seeders/SettingSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'logo' => 'logo.svg',
            'auth_logo' => 'auth_logo.png',
            'favicon' => 'favicon.svg',
            'TOP_HEADER_STYLE' => '1',
            'MAIN_MENU_STYLE' => '1',
            'CURRENCY_CODE' => 'BDT',
            'CURRENCY_CODE_MIN' => '৳',
            'CURRENCY_ICON' => '৳',
            'COUNTRY_SERVE' => 'Bangladesh',
            'SITE_INFO_PHONE' => '+8801749699156',
            'SITE_INFO_ADDRESS' => 'Dhaka, Bangladesh',
            'shipping_charge' => '60',
            'shipping_charge_out_of_range' => '120',
            'shipping_free_above' => '5000',
            'CHECKOUT_TYPE' => '1',
            'GUEST_CHECKOUT' => '1',
            'is_point' => '0',
            'Default_Point' => '0',
            'facebook' => 'https://facebook.com',
            'instagram' => 'https://instagram.com',
            'youtube' => 'https://youtube.com',
            'whatsapp' => '+8801749699156',
            'g_cod' => '1',
            'g_bkash' => '1',
            'g_nagad' => '1',
        ];

        foreach ($settings as $name => $value) {
            Setting::updateOrCreate(['name' => $name], ['value' => $value]);
        }
    }
}
```

- [ ] **Step 7: Run test to verify it passes**

Run: `php artisan test tests/Feature/Seeders/SettingSeederTest.php`
Expected: PASS (3 tests).

- [ ] **Step 8: Commit**

```bash
git add phpunit.xml database/seeders/RoleSeeder.php database/seeders/Concerns/SeedsAssets.php database/seeders/SettingSeeder.php tests/Feature/Seeders/SettingSeederTest.php
git commit -m "feat(seed): idempotent roles, asset helper, site settings seeder"
```

---

## Task 2: Catalog — brands, categories, collections

**Files:**
- Create: `database/seeders/CatalogSeeder.php`
- Test: `tests/Feature/Seeders/CatalogSeederTest.php`

**Interfaces:**
- Consumes: `Database\Seeders\Concerns\SeedsAssets`.
- Produces: `CatalogSeeder` creating ≥6 brands, ≥6 categories (several `is_shown_on_homepage = 1`), ≥2 collections, all `status = 1`, `cover_photo` from disk.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Seeders/CatalogSeederTest.php`:

```php
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
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Seeders/CatalogSeederTest.php`
Expected: FAIL — `Class "Database\Seeders\CatalogSeeder" not found`.

- [ ] **Step 3: Create CatalogSeeder**

Create `database/seeders/CatalogSeeder.php`:

```php
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
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test tests/Feature/Seeders/CatalogSeederTest.php`
Expected: PASS (2 tests).

- [ ] **Step 5: Commit**

```bash
git add database/seeders/CatalogSeeder.php tests/Feature/Seeders/CatalogSeederTest.php
git commit -m "feat(seed): lighting brands, categories, collections"
```

---

## Task 3: Vendors — users + shop_info + vendor_account

**Files:**
- Create: `database/seeders/VendorSeeder.php`
- Test: `tests/Feature/Seeders/VendorSeederTest.php`

**Interfaces:**
- Consumes: `RoleSeeder` (vendor role), `SeedsAssets`.
- Produces: `VendorSeeder` creating 4 vendor users (`role_id` = vendor role), each with one `ShopInfo` and one `VendorAccount`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Seeders/VendorSeederTest.php`:

```php
<?php

use App\Models\Role;
use App\Models\ShopInfo;
use App\Models\User;
use App\Models\VendorAccount;
use Database\Seeders\RoleSeeder;
use Database\Seeders\VendorSeeder;

beforeEach(fn () => $this->seed(RoleSeeder::class));

it('seeds vendors with shop and account', function () {
    $this->seed(VendorSeeder::class);

    $vendorRoleId = Role::where('slug', 'vendor')->value('id');
    expect(User::where('role_id', $vendorRoleId)->count())->toBe(4);
    expect(ShopInfo::count())->toBe(4);
    expect(VendorAccount::count())->toBe(4);

    $vendor = User::where('role_id', $vendorRoleId)->first();
    expect($vendor->shop_info)->not->toBeNull();
});

it('VendorSeeder is idempotent', function () {
    $this->seed(VendorSeeder::class);
    $this->seed(VendorSeeder::class);

    expect(ShopInfo::count())->toBe(4);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Seeders/VendorSeederTest.php`
Expected: FAIL — `Class "Database\Seeders\VendorSeeder" not found`.

- [ ] **Step 3: Create VendorSeeder**

Create `database/seeders/VendorSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\ShopInfo;
use App\Models\User;
use App\Models\VendorAccount;
use Database\Seeders\Concerns\SeedsAssets;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    use SeedsAssets;

    public function run(): void
    {
        $vendorRoleId = Role::where('slug', 'vendor')->value('id') ?? 2;

        $shops = [
            ['shop' => 'Cozy Lighting', 'owner' => 'Tasnim Ahmed'],
            ['shop' => 'Aurora Studio', 'owner' => 'Rafiq Hasan'],
            ['shop' => 'Lumen House', 'owner' => 'Nadia Karim'],
            ['shop' => 'Glow Craft', 'owner' => 'Imran Sheikh'],
        ];

        foreach ($shops as $i => $shop) {
            $username = 'vendor' . ($i + 1);

            $user = User::firstOrCreate(
                ['username' => $username],
                [
                    'role_id' => $vendorRoleId,
                    'name' => $shop['owner'],
                    'refer' => 0,
                    'email' => $username . '@example.com',
                    'phone' => '0171000000' . ($i + 1),
                    'password' => Hash::make('password'),
                    'is_approved' => true,
                    'status' => true,
                    'cancel_attempt' => 0,
                    'avatar' => 'default.png',
                    'point' => 0,
                    'joining_date' => now()->toDateString(),
                    'joining_month' => now()->format('F'),
                    'joining_year' => now()->year,
                    'email_verified_at' => now(),
                    'wallate' => 0,
                    'remember_token' => Str::random(10),
                ]
            );

            ShopInfo::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'is_admin' => 0,
                    'name' => $shop['shop'],
                    'gmail' => $username . '@example.com',
                    'slug' => Str::slug($shop['shop']),
                    'address' => 'Dhaka, Bangladesh',
                    'profile' => $this->pickImage('category'),
                    'cover_photo' => $this->pickImage('banner'),
                    'description' => 'Handcrafted decorative lighting from ' . $shop['shop'] . '.',
                ]
            );

            VendorAccount::firstOrCreate(['vendor_id' => $user->id], ['amount' => 0]);
        }
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test tests/Feature/Seeders/VendorSeederTest.php`
Expected: PASS (2 tests).

- [ ] **Step 5: Commit**

```bash
git add database/seeders/VendorSeeder.php tests/Feature/Seeders/VendorSeederTest.php
git commit -m "feat(seed): vendor users with shop info and accounts"
```

---

## Task 4: Customers — UserFactory + CustomerSeeder

**Files:**
- Create: `database/factories/UserFactory.php`
- Create: `database/seeders/CustomerSeeder.php`
- Test: `tests/Feature/Seeders/CustomerSeederTest.php`

**Interfaces:**
- Consumes: `RoleSeeder` (user role).
- Produces: `UserFactory` (default `role_id` = user role); `CustomerSeeder` creating 25 customers (skips if customers already exist).

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Seeders/CustomerSeederTest.php`:

```php
<?php

use App\Models\Role;
use App\Models\User;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(fn () => $this->seed(RoleSeeder::class));

it('seeds 25 customers with the user role', function () {
    $this->seed(CustomerSeeder::class);

    $userRoleId = Role::where('slug', 'user')->value('id');
    expect(User::where('role_id', $userRoleId)->count())->toBe(25);
});

it('CustomerSeeder is idempotent', function () {
    $this->seed(CustomerSeeder::class);
    $this->seed(CustomerSeeder::class);

    $userRoleId = Role::where('slug', 'user')->value('id');
    expect(User::where('role_id', $userRoleId)->count())->toBe(25);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Seeders/CustomerSeederTest.php`
Expected: FAIL — `Class "Database\Seeders\CustomerSeeder" not found`.

- [ ] **Step 3: Create UserFactory**

Create `database/factories/UserFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'role_id' => Role::where('slug', 'user')->value('id') ?? 3,
            'name' => fake()->name(),
            'refer' => 0,
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->numerify('017########'),
            'password' => Hash::make('password'),
            'is_approved' => true,
            'status' => true,
            'cancel_attempt' => 0,
            'avatar' => 'default.png',
            'point' => 0,
            'joining_date' => now()->toDateString(),
            'joining_month' => now()->format('F'),
            'joining_year' => now()->year,
            'email_verified_at' => now(),
            'wallate' => 0,
            'remember_token' => Str::random(10),
        ];
    }
}
```

- [ ] **Step 4: Create CustomerSeeder**

Create `database/seeders/CustomerSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $userRoleId = Role::where('slug', 'user')->value('id') ?? 3;

        if (User::where('role_id', $userRoleId)->count() >= 25) {
            return;
        }

        User::factory()->count(25)->create(['role_id' => $userRoleId]);
    }
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/Seeders/CustomerSeederTest.php`
Expected: PASS (2 tests).

- [ ] **Step 6: Commit**

```bash
git add database/factories/UserFactory.php database/seeders/CustomerSeeder.php tests/Feature/Seeders/CustomerSeederTest.php
git commit -m "feat(seed): customer users via UserFactory"
```

---

## Task 5: Products — factories + ProductSeeder (images, pivots, reviews)

**Files:**
- Create: `database/factories/ProductFactory.php`
- Create: `database/factories/ReviewFactory.php`
- Create: `database/seeders/ProductSeeder.php`
- Test: `tests/Feature/Seeders/ProductSeederTest.php`

**Interfaces:**
- Consumes: `CatalogSeeder` (brands, categories), `VendorSeeder` (vendor users), `CustomerSeeder` (reviewers), `SeedsAssets`.
- Produces: ~45 products, each with `user_id` (vendor), `brand_id`, 1–2 categories on the `category_product` pivot, 2–3 `ProductImage` rows, and 1–4 reviews. Subset has `reach > 0`. All product writes wrapped in `Product::withoutSyncingToSearch`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Seeders/ProductSeederTest.php`:

```php
<?php

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\VendorSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $this->seed(CatalogSeeder::class);
    $this->seed(VendorSeeder::class);
    $this->seed(CustomerSeeder::class);
});

it('seeds products wired to brand, vendor, categories, images, reviews', function () {
    $this->seed(ProductSeeder::class);

    expect(Product::count())->toBeGreaterThanOrEqual(40);
    expect(Product::where('status', true)->count())->toBe(Product::count());
    expect(Product::where('reach', '>', 0)->count())->toBeGreaterThan(0);
    expect(ProductImage::count())->toBeGreaterThan(Product::count());
    expect(Review::count())->toBeGreaterThan(Product::count());

    $p = Product::with(['brand', 'user', 'categories'])->first();
    expect($p->brand)->not->toBeNull();
    expect($p->user)->not->toBeNull();
    expect($p->categories)->not->toBeEmpty();
    expect($p->image)->not->toBe('');
});

it('ProductSeeder is idempotent', function () {
    $this->seed(ProductSeeder::class);
    $count = Product::count();
    $this->seed(ProductSeeder::class);

    expect(Product::count())->toBe($count);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Seeders/ProductSeederTest.php`
Expected: FAIL — `Class "Database\Seeders\ProductSeeder" not found`.

- [ ] **Step 3: Create ProductFactory**

Create `database/factories/ProductFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $adjectives = ['Aurora', 'Helix', 'Nimbus', 'Lumen', 'Cascade', 'Orbit', 'Zephyr', 'Solace', 'Mirage', 'Halo', 'Drift', 'Ember'];
        $forms = ['Swirl', 'Ridge', 'Wave', 'Spiral', 'Twist', 'Bloom', 'Pleat', 'Ripple'];
        $types = ['Table Lamp', 'Lamp Shade', 'Bedside Lamp', 'Ambient Light', 'Pendant Lamp', 'Night Lamp'];

        $title = fake()->randomElement($adjectives) . ' ' . fake()->randomElement($forms) . ' ' . fake()->randomElement($types);
        $price = fake()->numberBetween(1200, 9000);

        return [
            'user_id' => 1,   // overridden by seeder
            'brand_id' => 1,  // overridden by seeder
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 999999),
            'title' => $title,
            'short_description' => fake()->sentence(12),
            'full_description' => fake()->paragraphs(3, true),
            'regular_price' => (string) $price,
            'discount_price' => fake()->boolean(40) ? (string) ($price - fake()->numberBetween(100, 500)) : null,
            'dis_type' => 1,
            'quantity' => fake()->numberBetween(5, 100),
            'unit' => 'pcs',
            'image' => 'default.png',  // overridden by seeder
            'shipping_charge' => 0,
            'point' => 0,
            'reach' => 0,
            'status' => true,
            'is_aproved' => 1,
            'type' => 0,
            'download_able' => 0,
            'refer' => 0,
        ];
    }
}
```

- [ ] **Step 4: Create ReviewFactory**

Create `database/factories/ReviewFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,    // overridden by seeder
            'product_id' => 1, // overridden by seeder
            'order_id' => null,
            'rating' => fake()->numberBetween(3, 5),
            'body' => fake()->sentence(14),
            'file' => '',
        ];
    }
}
```

- [ ] **Step 5: Create ProductSeeder**

Create `database/seeders/ProductSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\Concerns\SeedsAssets;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use SeedsAssets;

    public function run(): void
    {
        if (Product::count() >= 40) {
            return;
        }

        $vendorRoleId = Role::where('slug', 'vendor')->value('id') ?? 2;
        $userRoleId = Role::where('slug', 'user')->value('id') ?? 3;

        $vendorIds = User::where('role_id', $vendorRoleId)->pluck('id')->all();
        $customerIds = User::where('role_id', $userRoleId)->pluck('id')->all();
        $brandIds = Brand::pluck('id')->all();
        $categoryIds = Category::pluck('id')->all();
        $images = $this->imagePool('product');

        if ($vendorIds === [] || $brandIds === [] || $categoryIds === []) {
            return; // prerequisites missing
        }

        Product::withoutSyncingToSearch(function () use ($vendorIds, $customerIds, $brandIds, $categoryIds, $images) {
            for ($i = 0; $i < 45; $i++) {
                $product = Product::factory()->create([
                    'user_id' => fake()->randomElement($vendorIds),
                    'brand_id' => fake()->randomElement($brandIds),
                    'image' => $images === [] ? 'default.png' : fake()->randomElement($images),
                    'reach' => fake()->boolean(35) ? fake()->numberBetween(1, 500) : 0,
                ]);

                $product->categories()->attach(
                    fake()->randomElements($categoryIds, fake()->numberBetween(1, min(2, count($categoryIds))))
                );

                foreach (range(1, fake()->numberBetween(2, 3)) as $g) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'name' => $images === [] ? 'default.png' : fake()->randomElement($images),
                    ]);
                }

                if ($customerIds !== []) {
                    Review::factory()->count(fake()->numberBetween(1, 4))->create([
                        'product_id' => $product->id,
                        'user_id' => fake()->randomElement($customerIds),
                    ]);
                }
            }
        });
    }
}
```

- [ ] **Step 6: Run test to verify it passes**

Run: `php artisan test tests/Feature/Seeders/ProductSeederTest.php`
Expected: PASS (2 tests).

- [ ] **Step 7: Commit**

```bash
git add database/factories/ProductFactory.php database/factories/ReviewFactory.php database/seeders/ProductSeeder.php tests/Feature/Seeders/ProductSeederTest.php
git commit -m "feat(seed): lighting products with images, categories, reviews"
```

---

## Task 6: Orders — OrderFactory + OrderSeeder

**Files:**
- Create: `database/factories/OrderFactory.php`
- Create: `database/seeders/OrderSeeder.php`
- Test: `tests/Feature/Seeders/OrderSeederTest.php`

**Interfaces:**
- Consumes: `CustomerSeeder` (buyers), `ProductSeeder` (line items).
- Produces: ~30 orders, each with 1–3 `OrderDetails` (`seller_id` = product's vendor; `color`/`size` = `'N/A'`); order `subtotal`/`total` recomputed from lines.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Seeders/OrderSeederTest.php`:

```php
<?php

use App\Models\Order;
use App\Models\OrderDetails;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\OrderSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\VendorSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $this->seed(CatalogSeeder::class);
    $this->seed(VendorSeeder::class);
    $this->seed(CustomerSeeder::class);
    $this->seed(ProductSeeder::class);
});

it('seeds orders with line items and totals', function () {
    $this->seed(OrderSeeder::class);

    expect(Order::count())->toBe(30);
    expect(OrderDetails::count())->toBeGreaterThanOrEqual(30);
    expect(Order::where('total', '>', 0)->count())->toBe(30);

    $detail = OrderDetails::first();
    expect($detail->seller_id)->toBeGreaterThan(0);
    expect($detail->color)->toBe('N/A');
});

it('OrderSeeder is idempotent', function () {
    $this->seed(OrderSeeder::class);
    $this->seed(OrderSeeder::class);

    expect(Order::count())->toBe(30);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Seeders/OrderSeederTest.php`
Expected: FAIL — `Class "Database\Seeders\OrderSeeder" not found`.

- [ ] **Step 3: Create OrderFactory**

Create `database/factories/OrderFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => null, // overridden by seeder
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->numerify('017########'),
            'email' => fake()->safeEmail(),
            'country' => 'Bangladesh',
            'district' => fake()->city(),
            'address' => fake()->streetAddress(),
            'shipping_method' => 'Home Delivery',
            'payment_method' => 'Cash on Delivery',
            'subtotal' => 0,
            'discount' => 0,
            'total' => 0,
            'cart_type' => '0',
            'status' => fake()->numberBetween(0, 5),
            'pay_staus' => 0,
            'order_id' => (string) fake()->unique()->numberBetween(100000, 999999),
            'invoice' => '#' . fake()->unique()->numberBetween(100000, 999999),
        ];
    }
}
```

- [ ] **Step 4: Create OrderSeeder**

Create `database/seeders/OrderSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::count() >= 30) {
            return;
        }

        $userRoleId = Role::where('slug', 'user')->value('id') ?? 3;
        $customers = User::where('role_id', $userRoleId)->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach (range(1, 30) as $n) {
            $customer = $customers->random();

            $order = Order::factory()->create([
                'user_id' => $customer->id,
                'first_name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
            ]);

            $items = $products->random(min(fake()->numberBetween(1, 3), $products->count()));
            $subtotal = 0;

            foreach ($items as $product) {
                $qty = fake()->numberBetween(1, 3);
                $price = (float) ($product->discount_price ?: $product->regular_price);
                $line = $price * $qty;
                $subtotal += $line;

                OrderDetails::create([
                    'order_id' => $order->id,
                    'seller_id' => $product->user_id,
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'color' => 'N/A',
                    'size' => 'N/A',
                    'qty' => $qty,
                    'price' => $price,
                    'total_price' => $line,
                    'g_total' => $line,
                ]);
            }

            $order->update(['subtotal' => $subtotal, 'total' => $subtotal]);
        }
    }
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/Seeders/OrderSeederTest.php`
Expected: PASS (2 tests).

- [ ] **Step 6: Commit**

```bash
git add database/factories/OrderFactory.php database/seeders/OrderSeeder.php tests/Feature/Seeders/OrderSeederTest.php
git commit -m "feat(seed): customer orders with line items"
```

---

## Task 7: Content — sliders, banners, coupons, blogs

**Files:**
- Create: `database/seeders/ContentSeeder.php`
- Test: `tests/Feature/Seeders/ContentSeederTest.php`

**Interfaces:**
- Consumes: `RoleSeeder` + `AdminSeeder` (blog author), `CatalogSeeder` (blog category), `SeedsAssets`.
- Produces: sliders (one `is_feature`), banners, 2 coupons (`discount_type` `'percent'`/`'amount'`), 3 blogs with a working thumbnail copied into `public/uploads/blogs/`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Seeders/ContentSeederTest.php`:

```php
<?php

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Coupon;
use App\Models\Slider;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\ContentSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(CatalogSeeder::class);
});

it('seeds homepage content', function () {
    $this->seed(ContentSeeder::class);

    expect(Slider::where('status', true)->count())->toBeGreaterThanOrEqual(1);
    expect(Slider::where('is_feature', 1)->count())->toBeGreaterThanOrEqual(1);
    expect(Banner::where('status', true)->count())->toBeGreaterThanOrEqual(1);
    expect(Coupon::where('discount_type', 'percent')->count())->toBe(1);
    expect(Blog::count())->toBe(3);
});

it('ContentSeeder is idempotent', function () {
    $this->seed(ContentSeeder::class);
    $this->seed(ContentSeeder::class);

    expect(Coupon::where('code', 'WELCOME10')->count())->toBe(1);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Seeders/ContentSeederTest.php`
Expected: FAIL — `Class "Database\Seeders\ContentSeeder" not found`.

- [ ] **Step 3: Verify coupon discount_type semantics (read-only confirmation)**

Run: `grep -rn "discount_type ==" app/Http/Controllers`
Expected: shows `== 'percent'`. Confirms the seeder must use `'percent'` (and a non-`percent` value such as `'amount'` for flat discounts). No code change.

- [ ] **Step 4: Create ContentSeeder**

Create `database/seeders/ContentSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Slider;
use App\Models\User;
use Database\Seeders\Concerns\SeedsAssets;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContentSeeder extends Seeder
{
    use SeedsAssets;

    public function run(): void
    {
        // Sliders — first one featured.
        foreach (array_values($this->imagePool('slider')) as $i => $img) {
            Slider::firstOrCreate(
                ['image' => $img],
                ['url' => '#', 'status' => 1, 'is_feature' => $i === 0 ? 1 : 0, 'is_pop' => 0, 'is_sub' => 0]
            );
        }

        // Banners.
        foreach ($this->imagePool('banner') as $img) {
            Banner::firstOrCreate(['image' => $img], ['url' => '#', 'status' => 1, 'is_feature' => 0, 'is_pop' => 0, 'is_sub' => 0]);
        }

        // Coupons.
        Coupon::firstOrCreate(
            ['code' => 'WELCOME10'],
            ['description' => '10% off your first order', 'discount_type' => 'percent', 'discount' => 10, 'limit_per_user' => 1, 'total_use_limit' => 100, 'available_limit' => 100, 'expire_date' => now()->addYear()->toDateString(), 'status' => 1]
        );
        Coupon::firstOrCreate(
            ['code' => 'LIGHT500'],
            ['description' => '৳500 off orders over ৳5000', 'discount_type' => 'amount', 'discount' => 500, 'limit_per_user' => 1, 'total_use_limit' => 100, 'available_limit' => 100, 'expire_date' => now()->addMonths(6)->toDateString(), 'status' => 1]
        );

        // Blogs — copy a real image into uploads/blogs/ so the view path (uploads/blogs/<thumbnail>) resolves.
        $adminId = User::where('role_id', 1)->value('id') ?? User::value('id');
        $categoryId = Category::value('id');

        $thumb = 'demo-blog.webp';
        $blogDir = public_path('uploads/blogs');
        File::ensureDirectoryExists($blogDir);
        $source = collect(File::files(public_path('uploads/slider')))->first();
        if ($source && ! File::exists($blogDir . '/' . $thumb)) {
            File::copy($source->getPathname(), $blogDir . '/' . $thumb);
        }

        $posts = [
            'Choosing the Right Table Lamp for Cozy Evenings',
            'How 3D-Printed Shades Transform Ambient Light',
            'Care Tips for Your Decorative Lighting',
        ];
        foreach ($posts as $title) {
            Blog::firstOrCreate(
                ['title' => $title],
                ['user_id' => $adminId, 'category_id' => $categoryId, 'thumbnail' => $thumb, 'description' => fake()->paragraphs(4, true), 'status' => 1, 'is_admin' => 1]
            );
        }
    }
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/Seeders/ContentSeederTest.php`
Expected: PASS (2 tests).

- [ ] **Step 6: Commit**

```bash
git add database/seeders/ContentSeeder.php tests/Feature/Seeders/ContentSeederTest.php
git commit -m "feat(seed): sliders, banners, coupons, blogs"
```

---

## Task 8: Orchestrator + production guard + wiring

**Files:**
- Create: `database/seeders/DemoDataSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Test: `tests/Feature/Seeders/DemoDataSeederTest.php`

**Interfaces:**
- Consumes: all domain seeders.
- Produces: `DemoDataSeeder` (calls Catalog → Vendor → Customer → Product → Order → Content; aborts in production). `DatabaseSeeder` calls Role → Admin → Setting → DemoData.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Seeders/DemoDataSeederTest.php`:

```php
<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoDataSeeder;

it('full DatabaseSeeder populates a coherent storefront', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Setting::where('name', 'logo')->value('value'))->toBe('logo.svg');
    expect(Product::count())->toBeGreaterThanOrEqual(40);
    expect(Order::count())->toBeGreaterThanOrEqual(30);
    expect(User::count())->toBeGreaterThan(25); // admin + vendors + 25 customers
    expect(\Illuminate\Support\Facades\DB::table('category_product')->count())->toBeGreaterThan(0);
});

it('DemoDataSeeder skips demo data in production', function () {
    app()['env'] = 'production';

    $this->seed(DemoDataSeeder::class);

    expect(Product::count())->toBe(0);

    app()['env'] = 'testing';
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Seeders/DemoDataSeederTest.php`
Expected: FAIL — `Class "Database\Seeders\DemoDataSeeder" not found`.

- [ ] **Step 3: Create DemoDataSeeder**

Create `database/seeders/DemoDataSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('DemoDataSeeder skipped: not seeding demo data in production.');

            return;
        }

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
```

- [ ] **Step 4: Wire DatabaseSeeder**

Replace the `run()` body of `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call([
        RoleSeeder::class,
        AdminSeeder::class,
        SettingSeeder::class,
        DemoDataSeeder::class,
    ]);
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/Seeders/DemoDataSeederTest.php`
Expected: PASS (2 tests).

- [ ] **Step 6: Run the full suite + a clean rebuild**

Run: `php artisan test tests/Feature/Seeders`
Expected: all seeder tests PASS.

Run: `php artisan migrate:fresh --seed`
Expected: completes with no exceptions.

Run: `php artisan db:seed`
Expected: no unique-constraint errors; counts unchanged (idempotent).

- [ ] **Step 7: Commit**

```bash
git add database/seeders/DemoDataSeeder.php database/seeders/DatabaseSeeder.php tests/Feature/Seeders/DemoDataSeederTest.php
git commit -m "feat(seed): orchestrate demo data with production guard"
```

---

## Task 9: Manual storefront verification

**Files:** none (verification only).

- [ ] **Step 1: Set local Scout driver**

Ensure local `.env` has `SCOUT_DRIVER=null` (avoids Algolia calls when browsing). Add the line if missing, then `php artisan config:clear`.

- [ ] **Step 2: Rebuild and run**

Run: `php artisan migrate:fresh --seed` then `composer dev`.

- [ ] **Step 3: Verify the storefront**

Load `http://localhost:8000/`. Confirm:
- header logo renders (`logo.svg`),
- the slider carousel and banners show images,
- product grids are populated with real lamp photos and ৳ prices,
- category sections appear,
- `/admin` dashboard and a vendor dashboard show products and orders.

No commit (verification only). If any section is empty, note which homepage variable (see `app/Http/Controllers/HomeController.php`) lacks data and extend the matching seeder.

---

## Self-Review

**Spec coverage:** Settings (Task 1) · Brands/Categories/Collections (Task 2) · Vendors (Task 3) · Customers (Task 4) · Products + images + pivots + reviews (Task 5) · Orders + details (Task 6) · Sliders/Banners/Coupons/Blogs (Task 7) · DemoDataSeeder + prod guard + wiring + idempotent re-run (Task 8) · manual verification (Task 9). Spec's optional Unproduct/Campaign were dropped (YAGNI — not required to fill the primary homepage sections; can be added later if a section reads empty in Task 9).

**Placeholder scan:** every code step contains complete, runnable code; no TBD/TODO. Image filenames are resolved at runtime via `SeedsAssets`, not hardcoded.

**Type/name consistency:** trait method names `imagePool()`/`pickImage()` used consistently; relationship `categories()` (pivot `category_product`), `brand()`, `user()`, `images()`, `reviews()` match the models; `discount_type` uses `'percent'` per the controller check; Order/OrderDetails use only `$fillable` keys; product writes wrapped in `withoutSyncingToSearch`.
