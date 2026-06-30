# Admin Dashboard Audit & Fix — 2026-06-30

Full functional + UI/UX + reliability pass over the entire admin dashboard
(`/admin/*`). Every change below was verified by booting the app, rendering the
affected views, querying the database, sweeping all admin routes, and running the
Pest suite.

## How it was verified
- **Route sweep:** every no-param admin GET route exercised through the router →
  **102 / 102 return `<500`** (0 server errors). Before the fixes it was 100/102.
- **View renders:** dashboard, product index/edit/create/inhouse/low, product CSV
  export, order index, customer index, shop — all render (no exceptions).
- **DB checks (local SQLite):** `orders`=31 rows, `order_details`=68; newest order
  created via the live checkout; `order_details` columns match what checkout writes.
- **Tests:** `composer test` → **82 passed**. The **5 remaining failures are
  pre-existing** (fail on the untouched baseline too) and unrelated to this work.

## Bugs fixed (were 500-ing or silently broken)

| Area | Root cause | Fix |
|------|-----------|-----|
| **Product edit page 500** | `Product::extra_categories()` used Eloquent's default pivot name `extra_mini_category_product` (singular), but the shipped table is `extra_mini_category_products` (plural). The edit form reads `$product->extra_categories` only in edit mode, so create worked and edit 500'd. | Pinned the pivot table on the relationship + fixed the same wrong name in 4 raw queries (admin store, vendor store, storefront category filter, CSV export). The whole extra-mini-category feature was dead, not just edit. |
| `/admin/product/inhouse` **500** | Passed a non-paginated `get()` collection to a paginated view and omitted `$statuses/$brands/$categories`. | Paginate + pass the required view vars. |
| Dashboard "low stock" link **500** (`lowProduct`) | Same missing `$statuses/$brands`. | Pass the required view vars. |
| `/admin/shop` **500** | `showShop()` returned `null` when no `ShopInfo` row exists; the view reads `$shop_info->profile` unguarded. | `ShopInfo::firstOrNew(['user_id' => 1])`. |
| Product **"Approved" filter** did nothing | Switch matched `case 'aproved'` but the dropdown sends `approved`. | Fixed the typo. |

## UI/UX fixes

- **Action-button icons were blank everywhere.** The admin layout loaded only
  Boxicons; every admin table/button uses FontAwesome (`fas`/`fab`) classes, so the
  icons rendered as empty colored boxes. Loaded the self-hosted FontAwesome that the
  vendor layout already ships (`/assets/plugins/fontawesome-free/css/all.min.css`).
- Replaced the meaningless `fab fa-jedi-order` (a Star Wars glyph) with cart icons on
  the product list and the dashboard "Total Orders" tile.
- **Dashboard stat tiles** are now a proper drill-down: the **whole tile is the link**
  (via a transparent stretched-link overlay) with a "View details →" affordance,
  replacing the vague "More info" footer.
  - The colored background must stay on the `<div>`, **not** on an `<a>`: adminlte.css
    ships an unlayered `a { background-color: transparent }` (Bootstrap reboot) which,
    per CSS cascade layers, overrides Tailwind's layered `bg-tile-*` utility and would
    render the tile white. The overlay `<a>` is transparent, so it's unaffected.
  - `tests/Feature/Ui/StatTileComponentTest.php` updated for the new contract.

## Order system — investigated, working (no code change)
The reported "empty order list" is **not** broken: the admin list is a correct,
unfiltered `Order::latest()->paginate(10)`, and the data flow
**checkout → order → order_details → admin list** works (verified against real rows).
The earlier emptiness was the malware-wiped `OrderController`, already restored in
commit `84ecda9`.

## Known / not changed (intentional)
- **`multi_order` (multi-vendor per-order split)** is not populated at checkout, so
  per-vendor sub-status buttons update 0 rows. This is **tolerated by design** — a
  regression test (`Vendor/OrderListTest`) asserts the views must not crash on missing
  rows. Populating it risks double-counting in the commission / partial-payment math.
- **Pre-existing security findings** (see `docs/CODE_REVIEW.md`): unauthenticated
  contact file upload, open notification endpoint, hardcoded Firebase/BDCourier keys.
  Out of scope for this dashboard pass — fix separately.
- Dashboard has a few computed-but-unused stats (`return_orders` is mislabeled
  status 4 = Shipping; `fraud_orders`/`bounce_rate` hardcoded `0`). Harmless (not
  displayed); left as-is.

## Deployment & rollback
- `public/build/*` is **gitignored**. The dashboard CSS change takes effect when the
  deploy runs `npm run build` (deploy.yml). Locally, run `composer dev` (server +
  Vite together) or `npm run build`; do not leave a stale `public/hot` file without a
  running Vite dev server, or `@vite` will point at an offline dev server and Tailwind
  won't load.
- **Rollback:** this landed as a single commit — revert with `git revert <sha>`.
