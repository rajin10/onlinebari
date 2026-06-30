# Homepage: unified, dynamic product card (issue #6, items #4 & #5)

Date: 2026-06-24
Branch: `feat/issue-6-homepage-cards`
Refs: hasib-devs/eureka#6 (items 4 and 5)

## Problem

On the storefront homepage (`resources/views/frontend/index.blade.php`):

- **#4** Two different product-card designs are in use. The "Robot-Crafted 3D Art" /
  "Cozy Lighting" featured section uses a premium card (`.lux-product-card`,
  inline at ~lines 1044–1095). The per-category sections (Table Lamps, Lamp
  Shades, Ceiling Lights, Wall Lights, Bedside Lamps — looped from
  `$homepage_category_products`) use a different, older `x-product-grid-view`
  component. Every category section should use the lux card, and new categories
  flagged `is_shown_on_homepage` should get it automatically.
- **#5** On the lux card, the star rating ("★★★★★ 45 Review") and the three
  colour swatches are **hardcoded**. They must be **dynamic per product**, pulled
  from `Product::reviews()` and `Product::colors()` (the data layer already
  exists: `reviews` table; `color_product` pivot with `color_id/product_id/qnty/price`).
  Demo colours and reviews are seeded so the feature is visibly testable.

## Scope

Homepage only. Other pages (category listing, search, etc.) keep
`x-product-grid-view` unchanged. Wishlist remains the existing static button
(not wired). The featured "Robot-Crafted 3D Art" section stays as the reference.

## Design

### 1. New component `<x-lux-product-card>`
`resources/views/components/lux-product-card.blade.php`. Props:
`Product $product`, `?string $category = null`.

Data-driven version of the current `.lux-product-card` markup:
- **Image**: first entry of `$product->image` (JSON array) → `uploads/product/…`,
  with a placeholder fallback. Title/slug link unchanged.
- **Rating (#5)**: `avg = reviews->avg('rating')`, `count = reviews->count()`.
  Render filled/empty stars from the rounded average; label `"{count} Review(s)"`.
  Zero reviews → empty stars + "No reviews".
- **Colours (#5)**: loop `$product->colors`, each swatch `style="background:{code}"`.
  Cap at 4 visible with a `+N` chip for the remainder. No colours → omit the
  swatch group (rating stays on its row).
- **Category label**: `$category` prop if given, else the product's own
  category, else "Cozy Lighting".
- **Price**: `discount_price ?? regular_price`, `number_format`. Add-to-Cart form
  + `ajax-lux-cart-btn` unchanged.

### 2. Homepage edits (`frontend/index.blade.php`)
- Featured section: replace the inline `@php … <div class="lux-product-card">…</div>`
  block with `<x-lux-product-card :product="$product" />` inside the existing
  `.product-slider` carousel.
- Per-category sections: replace the `.row.autoplay.slick-slides` slider with a
  responsive grid (`grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4`) of
  `<x-lux-product-card :product="$product" :category="$homepage_category->name" />`,
  keeping each "View All" heading. `x-product-empty-component` stays for empties.

### 3. `HomeController::__invoke`
- `$products`: add `colors` and category to `with([...])`.
- `$homepage_category_products`: add `products.colors` to `with([...])`
  (`products.reviews` already eager-loaded). Avoids N+1 from the card.

### 4. Demo seeder
`database/seeders/DemoVariationSeeder.php`, idempotent, registered in
`DatabaseSeeder` and runnable via `php artisan db:seed --class=DemoVariationSeeder`:
- Create ~5 colours (Black `#000000`, White `#FFFFFF`, Gold `#FFCC00`,
  Charcoal `#36454F`, Warm White `#FDF4DC`), `status = true`, keyed by slug.
- Attach 2–4 random colours to each of the first ~24 active products via
  `color_product` (skip pairs already present).
- Seed 1–3 reviews (rating 3–5, short body) per those products, authored by an
  existing user, skipping products that already have reviews.

## Testing / verification

- Run the seeder; load the homepage in-browser (desktop + mobile): every section
  renders the lux card; swatches reflect each product's seeded colours; stars and
  counts reflect seeded reviews; the per-category grid lays out correctly.
- A Pest test asserting the homepage renders the new component for a product with
  colours + reviews and shows its swatch colours / review count.
- `composer test` green; `vendor/bin/pint` clean.

## Out of scope

Wishlist wiring; the odd `category_id != ['13','9']` featured query; moving the
large inline `.lux-*` CSS out of `index.blade.php`; non-homepage card surfaces.
