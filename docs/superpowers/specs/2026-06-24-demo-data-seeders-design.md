# Demo Data Seeders — Design

**Date:** 2026-06-24
**Status:** Approved (approach A)
**Goal:** The freshly-migrated local app looks empty (no products, categories, sliders, logo).
Add seeders that populate a coherent, full demo dataset so the storefront, admin, and vendor
dashboards all look populated for local development and demos.

## Context

- **Stack:** Laravel 11 · PHP 8.2+ · SQLite (local) · multi-vendor e-commerce.
- **Niche (read from the committed product photos):** decorative / 3D-printed **home lighting**
  — table lamps, lamp shades, ambient lights. Currency is Bangladeshi Taka (৳).
- **Existing seeders:** `DatabaseSeeder` → `RoleSeeder` (roles: `admin`, `vendor`, `user`),
  `AdminSeeder` (admin user `admin@gmail.com` / `password`). No factories exist.
- **Images already on disk** (reused so nothing renders broken):
  `public/uploads/product/` (59 `.webp`), `category/` (5), `slider/` (3), `banner/` (1),
  `collection/` (1), `setting/` (logo.svg, auth_logo.png, favicon.svg).
- **Homepage data needs** (`app/Http/Controllers/HomeController.php`): sliders (status, not
  pop/sub/feature), featured sliders, banners, `ShopInfo` shops, flash `Campaign`s, products
  via the `category_product` pivot (`status=1`), `reach`-ranked products, `Unproduct`s,
  `Collection`s, `Category` rows with `is_shown_on_homepage=1` that `has('products')`,
  `HomepageVideo`.

## Approach

**A — Factories + curated seeders, reusing the committed images.** Curate the small fixed sets
(settings, categories, brands, sliders, banners, collections, coupons) by hand so they match the
lighting niche; use Laravel factories + Faker for the volume entities (customers, products,
reviews, orders). Products are wired to real image files and `category_product` pivots.

Rejected: **B** all-hand-written (too verbose for 25 customers + 30 orders); **C** Faker-only
(random titles won't match the lamp photos → incoherent).

## Entities to seed

Orchestrated by a new `DemoDataSeeder`. Counts are targets, not hard requirements.

| Entity | Count | Key fields / notes |
|---|---|---|
| **Settings** | full set | site name, **logo/auth_logo/favicon**, currency `৳`/Taka, `TOP_HEADER_STYLE`, contact/footer fields. Fixes the original missing-logo bug. |
| **Brands** | 6–8 | lighting brands (Cozy Lighting, Aurora, Lumen…); `cover_photo` from disk; `status=1` |
| **Categories** | 6–8 | Table Lamps, Ceiling, Wall, Lamp Shades, Ambient, Outdoor; `cover_photo` from disk; several `is_shown_on_homepage=1`; `status=1` |
| **Collections** | 2–3 | New Arrivals, Best Sellers; `cover_photo` from disk |
| **Vendors** | 3–4 | user (role=vendor, `is_approved=1`) + `ShopInfo` + `VendorAccount` |
| **Customers** | ~25 | user (role=user); Bangladeshi names/phones; `joining_date/month/year` set |
| **Products** | ~45 | `user_id`=vendor, `brand_id` (required), 1+ categories via `category_product`; main `image` + `ProductImage` gallery (2–3) from the 59 files; Taka `regular_price`/`discount_price`; `quantity`; `status=1`, `is_aproved=1`; subset `reach>0` for "popular" |
| **Reviews** | ~3/product | `rating` 3–5, `body`, `file` (NOT NULL → `''` or image name), tied to customers |
| **Sliders** | 3 | from slider images; subset `is_feature=1`; `status=1` |
| **Banners** | 1–3 | from banner image; `status=1` |
| **Orders + order_details** | ~30 | customer orders across vendors/products; varied `status`; `order_details` needs `seller_id`, `title`, `color`, `size` (default `'N/A'`), `qty`, `price`, `total_price`, `g_total` |
| **Coupons** | 2 | `code`, `discount_type`, `discount`, limits, `expire_date` future, `status=1` |
| **Blogs** | 3 | `user_id`, `category_id` (NOT NULL FK), `thumbnail`, `description`, `status=1` |
| **Unproducts** | 3–4 *(optional)* | fills the homepage "unproduct" section; `user_id`, `title`, `slug`, `price`, `contact`, `location`, `thumbnail` |
| **Campaign (flash)** *(optional)* | 1 | `is_flash=1`, future `end` → renders the homepage flash-sale section |

## Key strategies

- **Images:** each seeder lists the relevant `public/uploads/{product,category,slider,banner,
  collection}` directory at runtime and assigns real filenames (cycling for products). Guarantees
  zero broken images and no dependence on hardcoded hashes.
- **Idempotency:** convert `RoleSeeder` and `AdminSeeder` to `firstOrCreate`/`updateOrCreate`
  (this removes the `UNIQUE constraint failed: roles.slug` crash on re-run). Each demo seeder
  guards by count (skip its section if its table already has demo rows), so `db:seed` is a safe
  top-up.
- **Production guard:** `DemoDataSeeder` aborts early when `app()->isProduction()` so it can never
  pollute the live MySQL. Essential seeders (`RoleSeeder`, `AdminSeeder`, `SettingSeeder`) still
  run in every environment.
- **Wiring:** `DatabaseSeeder` calls `RoleSeeder`, `AdminSeeder`, `SettingSeeder` always, then
  `DemoDataSeeder` (which fans out to the per-domain seeders below).
- **Run model:**
  - `php artisan migrate:fresh --seed` → clean, complete demo dataset.
  - `php artisan db:seed` → safe re-run / top-up (no crashes, no duplicates).

## File structure

```
database/seeders/
  DatabaseSeeder.php      # edit: call SettingSeeder + DemoDataSeeder
  RoleSeeder.php          # edit: firstOrCreate (idempotent)
  AdminSeeder.php         # edit: updateOrCreate (idempotent)
  SettingSeeder.php       # new: site settings incl. logo/currency/header
  DemoDataSeeder.php      # new: prod-guard + orchestrates the rest
  CatalogSeeder.php       # new: brands, categories, collections
  VendorSeeder.php        # new: vendor users + ShopInfo + VendorAccount
  CustomerSeeder.php      # new: ~25 customers (factory)
  ProductSeeder.php       # new: ~45 products + images + pivots + reviews
  OrderSeeder.php         # new: ~30 orders + order_details
  ContentSeeder.php       # new: sliders, banners, coupons, blogs, (unproduct/campaign)
database/factories/
  UserFactory.php         # new
  ProductFactory.php      # new
  ReviewFactory.php       # new
  OrderFactory.php        # new
```

## Testing / verification

1. `php artisan migrate:fresh --seed` runs clean (no exceptions).
2. Row-count assertions: products ≥ 40, categories ≥ 6, brands ≥ 6, sliders ≥ 3, orders ≥ 20,
   `category_product` non-empty, settings has `logo`.
3. `php artisan db:seed` a second time → no unique-constraint errors, no duplicate explosion.
4. Manual: `composer dev`, load `/` — header logo shows, sliders/banners render, product grids
   populated with real lamp photos and ৳ prices; admin/vendor dashboards show products & orders.

## Prerequisites / notes

- The git worktree has **no `vendor/`**. Before running artisan in the worktree, run
  `composer install` there (or run/test from the main repo, which shares the branch via git).
- The local `.env` was switched to SQLite (`DB_CONNECTION=sqlite`, `APP_ENV=local`,
  `APP_URL=http://localhost:8000`); MySQL prod values kept as comments. The `SettingSeeder` makes
  the earlier manual `logo`/`auth_logo`/`favicon` rows reproducible.

## Out of scope

- Tests/analytics dashboards beyond making them look populated.
- Image generation — we reuse committed assets only.
- Production data — demo seeders are explicitly guarded off in production.
