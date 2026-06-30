# Vendor Tailwind Migration — Plan 1 of N: Dashboard Milestone

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rebuild the vendor **shell** (layout + sidebar + navbar) and the **dashboard page** in Tailwind + Alpine, matching the existing admin dashboard look exactly (white/gold sectioned sidebar, solid stat tiles), with AdminLTE no longer styling these pieces — as the first shippable, parity-verified milestone of the vendor migration.

**Architecture:** A new Tailwind dashboard shell replaces AdminLTE's `.wrapper/.main-sidebar/.main-header` in `layouts/vendor/app.blade.php`. Sidebar collapse and submenu toggling move from AdminLTE JS (`data-widget`) to **Alpine**. `app.css` is loaded **last** in the vendor layout (so Tailwind wins) with a small scoped reset; `adminlte.min.css` **stays loaded for now** because the other 18 vendor views are still AdminLTE-styled (removed in the final plan). The dashboard's AdminLTE `small-box` tiles become a token-driven `<x-ui.stat-tile>`.

**Tech Stack:** Laravel 11 · Blade · Tailwind CSS v4 · Alpine.js · boxicons · Pest.

## Global Constraints

- **OVERRIDING:** Everything must look **exactly the same** as the reference (the admin dashboard look) — rebuild to visual parity, **never a redesign**. Any pixel diff is a defect. Reference: `eureka.reliableuksolutions.com/admin/dashboard` and the existing `dashboard-assets/style.css` sidebar.
- Dashboards use the **gold** accent `#f2d231` (active/hover), text `#000`; storefront keeps orange. Do not use `--color-primary` (orange) for dashboard chrome.
- Stat tiles use the **solid** small-box colors: info `#17a2b8`, warning `#ffc107`, success `#28a745`, primary `#007bff`, danger `#dc3545`, white text (gold/warning keeps white text as today).
- **Keep `adminlte.min.css` loaded** in the vendor layout this plan (other views still need it). Do NOT remove it yet.
- Keep jQuery, Bootstrap bundle JS, notify, the `#deleteData` confirm, and all per-page JS-widget plugins (DataTables/select2/summernote/dropzone).
- No new inline `style=""` or `<style>` blocks. Before each commit: `vendor/bin/pint` (changed files), `composer test`, `npm run build`.
- Vendor menu content stays vendor's own (Dashboard / Products / Orders / Profile / Withdraw / Logout) — only the styling matches the admin shell.

**Scope of this plan:** tokens; `<x-ui.stat-tile>`; rebuilt `layouts/vendor/app.blade.php` + `partials/navbar.blade.php` + `partials/aside.blade.php`; converted `vendor/dashboard.blade.php`; parity verification. **Out of scope (later plans):** the other 18 vendor views, the table/form/modal/tabs/alert components they need, and removing AdminLTE.

---

### Task 1: Add dashboard accent + stat-tile tokens

**Files:**
- Modify: `resources/css/app.css` (the `@theme` block)

**Interfaces:**
- Produces utilities: `bg-accent`, `text-accent`, `text-accent-fg`, `bg-accent-fg`, and `bg-tile-{info,warning,success,primary,danger}` / `text-tile-*`.

- [ ] **Step 1: Add the tokens**

In `resources/css/app.css`, inside the `@theme { … }` block, after the `--color-info` line, add:

```css
    /* ---- Dashboard accent (gold) + stat-tile solids (match AdminLTE small-box) ---- */
    --color-accent:       #f2d231;
    --color-accent-fg:    #000000;
    --color-tile-info:    #17a2b8;
    --color-tile-warning: #ffc107;
    --color-tile-success: #28a745;
    --color-tile-primary: #007bff;
    --color-tile-danger:  #dc3545;
```

- [ ] **Step 2: Build and verify the tokens compile**

Run: `npm run build`
Expected: exit 0.

- [ ] **Step 3: Verify the accent token is emitted**

Run: `grep -c 'f2d231' public/build/assets/app-*.css`
Expected: prints a non-zero count (≥1).

- [ ] **Step 4: Commit**

```bash
git add resources/css/app.css
git commit -m "feat(css): add dashboard gold accent and stat-tile color tokens"
```

---

### Task 2: `<x-ui.stat-tile>` component

The AdminLTE `small-box` rebuilt in Tailwind: a solid-colored tile with a big value, label, a large faded corner icon, and a darker "More info" footer link.

**Files:**
- Create: `resources/views/components/ui/stat-tile.blade.php`
- Test: `tests/Feature/Ui/StatTileComponentTest.php`

**Interfaces:**
- Produces: `<x-ui.stat-tile variant="info|warning|success|primary|danger" :value="$n" label="Total Products" icon="fas fa-procedures" :href="route(...)">`. `href` optional → renders the footer link only when present.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Ui/StatTileComponentTest.php`:

```php
<?php

use Illuminate\Support\Facades\Blade;

it('renders an info tile with value, label and solid color', function () {
    $html = Blade::render('<x-ui.stat-tile variant="info" :value="45" label="Total Products" icon="fas fa-box" />');

    expect($html)->toContain('45')
        ->toContain('Total Products')
        ->toContain('bg-tile-info');
});

it('renders the danger variant color', function () {
    $html = Blade::render('<x-ui.stat-tile variant="danger" :value="2" label="Cancel" icon="fas fa-x" />');

    expect($html)->toContain('bg-tile-danger');
});

it('renders a More info footer link when href is given', function () {
    $html = Blade::render('<x-ui.stat-tile variant="success" :value="30" label="Orders" icon="fas fa-cart" href="/vendor/order" />');

    expect($html)->toContain('More info')->toContain('href="/vendor/order"');
});

it('omits the footer link when no href is given', function () {
    $html = Blade::render('<x-ui.stat-tile variant="info" :value="0" label="Amount" icon="fas fa-money" />');

    expect($html)->not->toContain('More info');
});

it('renders the icon class', function () {
    $html = Blade::render('<x-ui.stat-tile variant="info" :value="1" label="X" icon="fas fa-procedures" />');

    expect($html)->toContain('fas fa-procedures');
});
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `vendor/bin/pest tests/Feature/Ui/StatTileComponentTest.php`
Expected: FAIL — `ui.stat-tile` view does not exist.

- [ ] **Step 3: Create the component**

Create `resources/views/components/ui/stat-tile.blade.php`:

```blade
@props([
    'variant' => 'info',
    'value' => '',
    'label' => '',
    'icon' => '',
    'href' => null,
])

@php
    $tiles = [
        'info'    => 'bg-tile-info',
        'warning' => 'bg-tile-warning',
        'success' => 'bg-tile-success',
        'primary' => 'bg-tile-primary',
        'danger'  => 'bg-tile-danger',
    ];
    $bg = $tiles[$variant] ?? $tiles['info'];
@endphp

<div {{ $attributes->merge(['class' => "relative overflow-hidden rounded-lg text-white shadow-sm $bg"]) }}>
    <div class="p-4">
        <h3 class="text-3xl font-bold leading-none">{{ $value }}</h3>
        <p class="mt-1 text-sm">{{ $label }}</p>
    </div>

    @if ($icon)
        <i class="{{ $icon }} pointer-events-none absolute right-3 top-3 text-5xl text-white/30"></i>
    @endif

    @if ($href)
        <a href="{{ $href }}" class="flex items-center justify-center gap-1 bg-black/10 py-2 text-sm text-white/90 transition-colors hover:bg-black/20">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    @endif
</div>
```

- [ ] **Step 4: Run the test to verify it passes**

Run: `vendor/bin/pest tests/Feature/Ui/StatTileComponentTest.php`
Expected: PASS (5 passed).

- [ ] **Step 5: Commit**

```bash
vendor/bin/pint resources/views/components/ui/stat-tile.blade.php tests/Feature/Ui/StatTileComponentTest.php
git add resources/views/components/ui/stat-tile.blade.php tests/Feature/Ui/StatTileComponentTest.php
git commit -m "feat(ui): add x-ui.stat-tile (small-box) component"
```

---

### Task 3: Rebuild the vendor sidebar (`aside`) in Tailwind + Alpine

Replace the AdminLTE dark sidebar with the admin-style white/gold sectioned sidebar. Alpine drives the collapsible submenus. Preserve every menu item and `Request::is()` active rule from the current `aside.blade.php`.

**Files:**
- Overwrite: `resources/views/layouts/vendor/partials/aside.blade.php`

**Interfaces:**
- Consumes: gold accent classes from Task 1.
- Produces: an `<aside>` whose root has `x-data` driving submenu open/close; expects the parent layout (Task 5) to provide the `sidebarOpen` Alpine scope for mobile show/hide. Uses boxicons (`bx bx-*`), matching the admin sidebar.

- [ ] **Step 1: Overwrite the file**

Replace the entire contents of `resources/views/layouts/vendor/partials/aside.blade.php` with:

```blade
@php
    $navActive = fn ($patterns) => request()->is($patterns) ? 'bg-accent text-accent-fg' : 'text-slate-700 hover:bg-accent hover:text-accent-fg';
@endphp

<aside class="dash-sidebar fixed inset-y-0 left-0 z-40 flex w-64 shrink-0 flex-col overflow-y-auto border-r border-slate-200 bg-white transition-transform lg:static lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       x-data="{ open: { products: {{ request()->is('vendor/product*') ? 'true' : 'false' }}, profile: {{ request()->is('vendor/profile*') ? 'true' : 'false' }} } }">

    <div class="border-b border-slate-200 px-4 py-4">
        <img src="/uploads/setting/{{ setting('logo') }}" alt="Logo" class="max-h-10">
    </div>

    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-3">
        <img src="{{ Auth::user()->avatar != 'default.png' ? '/uploads/member/'.Auth::user()->avatar : '/default/user.jpg' }}"
             class="h-10 w-10 rounded-full object-cover" alt="User">
        <span class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</span>
    </div>

    <nav class="flex flex-1 flex-col gap-1 p-2 text-sm">
        <a href="{{ routeHelper('dashboard') }}"
           class="flex items-center gap-3 rounded-md px-3 py-2 {{ $navActive('vendor/dashboard') }}">
            <i class="bx bxs-dashboard text-lg"></i> Dashboard
        </a>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 rounded-md px-3 py-2 text-slate-700 hover:bg-accent hover:text-accent-fg">
            <i class="bx bx-user text-lg"></i> Customer Panel
        </a>

        <div>
            <button type="button" @click="open.products = !open.products"
                    class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-slate-700 hover:bg-accent hover:text-accent-fg">
                <i class="bx bx-package text-lg"></i> Products
                <i class="bx bx-chevron-down ml-auto transition-transform" :class="open.products && 'rotate-180'"></i>
            </button>
            <ul x-show="open.products" x-cloak class="ml-4 mt-1 flex flex-col gap-1 border-l border-slate-200 pl-2">
                <li><a href="{{ routeHelper('product/create') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/product/create') }}"><i class="bx bx-plus-circle"></i> Add</a></li>
                <li><a href="{{ routeHelper('product') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/product') }}"><i class="bx bx-list-ul"></i> List</a></li>
                <li><a href="{{ route('vendor.low.product') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-slate-700 hover:bg-accent hover:text-accent-fg"><i class="bx bx-error"></i> Low qnty</a></li>
                <li><a href="{{ routeHelper('product/active') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/product/active') }}"><i class="bx bx-like"></i> Active</a></li>
                <li><a href="{{ routeHelper('product/disable') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/product/disable') }}"><i class="bx bx-dislike"></i> Disable</a></li>
            </ul>
        </div>

        <div>
            <button type="button" @click="open.profile = !open.profile"
                    class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-slate-700 hover:bg-accent hover:text-accent-fg">
                <i class="bx bx-user-circle text-lg"></i> Profile
                <i class="bx bx-chevron-down ml-auto transition-transform" :class="open.profile && 'rotate-180'"></i>
            </button>
            <ul x-show="open.profile" x-cloak class="ml-4 mt-1 flex flex-col gap-1 border-l border-slate-200 pl-2">
                <li><a href="{{ routeHelper('profile') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-slate-700 hover:bg-accent hover:text-accent-fg"><i class="bx bx-user"></i> My Profile</a></li>
                <li><a href="{{ routeHelper('profile/change-password') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-slate-700 hover:bg-accent hover:text-accent-fg"><i class="bx bx-key"></i> Change Password</a></li>
            </ul>
        </div>

        <a href="{{ route('vendor.withdraw') }}"
           class="flex items-center gap-3 rounded-md px-3 py-2 {{ $navActive('withdraw*') }}">
            <i class="bx bx-money text-lg"></i> Withdraw
        </a>

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="mt-auto flex items-center gap-3 rounded-md px-3 py-2 text-slate-700 hover:bg-accent hover:text-accent-fg">
            <i class="bx bx-power-off text-lg"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </nav>
</aside>
```

- [ ] **Step 2: Build (no test — visual; verified in Task 6)**

Run: `npm run build`
Expected: exit 0. (Visual correctness is verified end-to-end in Task 6.)

- [ ] **Step 3: Commit**

```bash
git add resources/views/layouts/vendor/partials/aside.blade.php
git commit -m "feat(vendor): rebuild sidebar in Tailwind + Alpine (white/gold, boxicons)"
```

---

### Task 4: Rebuild the vendor navbar in Tailwind + Alpine

**Files:**
- Overwrite: `resources/views/layouts/vendor/partials/navbar.blade.php`

**Interfaces:**
- Produces: a top bar with an Alpine hamburger that toggles the layout's `sidebarOpen` scope (Task 5), a Visit Site link, and a logout link.

- [ ] **Step 1: Overwrite the file**

Replace the entire contents of `resources/views/layouts/vendor/partials/navbar.blade.php` with:

```blade
<header class="flex h-14 items-center gap-4 border-b border-slate-200 bg-white px-4">
    <button type="button" @click="sidebarOpen = !sidebarOpen" class="text-slate-600 hover:text-slate-900 lg:hidden" aria-label="Toggle sidebar">
        <i class="bx bx-menu text-2xl"></i>
    </button>

    <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
        <i class="bx bx-globe"></i> Visit Site
    </a>

    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();"
       class="ml-auto inline-flex items-center gap-2 rounded-md bg-accent px-3 py-1.5 text-sm font-medium text-accent-fg">
        <i class="bx bx-power-off"></i> Logout
    </a>
    <form id="logout-form-top" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
</header>
```

- [ ] **Step 2: Build**

Run: `npm run build`
Expected: exit 0.

- [ ] **Step 3: Commit**

```bash
git add resources/views/layouts/vendor/partials/navbar.blade.php
git commit -m "feat(vendor): rebuild navbar in Tailwind + Alpine"
```

---

### Task 5: Rebuild the vendor layout shell (Tailwind + Alpine; app.css last; keep AdminLTE loaded)

**Files:**
- Overwrite: `resources/views/layouts/vendor/app.blade.php`
- Modify: `resources/css/app.css` (add a scoped dashboard reset)

**Interfaces:**
- Consumes: `partials/navbar` (Task 4), `partials/aside` (Task 3).
- Produces: the Alpine `sidebarOpen` scope on `<body>`; a Tailwind flex shell (`<aside> + <main>`); `app.css` loaded **after** `adminlte.min.css` so Tailwind wins on these pages.

- [ ] **Step 1: Add a scoped dashboard reset to `app.css`**

Because global Preflight is off, the new shell needs minimal base resets. In `resources/css/app.css`, **after** the `@theme { … }` block (at end of file), add:

```css
/* Scoped reset for the rebuilt Tailwind dashboard shell (global Preflight stays off). */
@layer base {
    .dash-shell *, .dash-shell *::before, .dash-shell *::after { box-sizing: border-box; }
    .dash-shell { margin: 0; -webkit-text-size-adjust: 100%; line-height: 1.5; }
    .dash-shell h1, .dash-shell h2, .dash-shell h3, .dash-shell p { margin: 0; }
    .dash-shell img { display: block; max-width: 100%; height: auto; }
    .dash-shell ul { margin: 0; padding: 0; list-style: none; }
    .dash-shell button { background: none; border: 0; cursor: pointer; font: inherit; color: inherit; }
}

/* Hide Alpine x-cloak elements until Alpine initialises (prevents submenu flash). */
[x-cloak] { display: none !important; }
```

- [ ] **Step 2: Overwrite the vendor layout**

Replace the entire contents of `resources/views/layouts/vendor/app.blade.php` with:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/jpg" href="/uploads/setting/{{ setting('favicon') }}"/>
    <title>@yield('title')</title>

    @include('layouts.global')

    <!-- Source Sans Pro (dashboard font) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- boxicons (sidebar icons) -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Font Awesome (stat-tile + content icons) -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">

    @notifyCss
    @stack('css')

    <!-- AdminLTE still loaded: the other vendor views remain AdminLTE-styled until their plans. -->
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css">

    <!-- Tailwind LAST so it wins on the rebuilt pieces. -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="dash-shell min-h-screen bg-slate-100 font-dash text-slate-700"
      x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        @include('layouts.vendor.partials.aside')

        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
             class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

        <div class="flex min-w-0 flex-1 flex-col">
            @include('layouts.vendor.partials.navbar')
            <main class="flex-1 p-4">
                @yield('content')
            </main>
            <footer class="border-t border-slate-200 bg-white py-2 text-center text-xs text-slate-500">
                Laravel Ecommerce system by: Finva Soft Limited
            </footer>
        </div>
    </div>

    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap bundle (still used by un-migrated views' JS widgets) -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <x-notify::notify />
    @notifyJs
    @stack('js')

    <script>
        setInterval(function () { $('.notify').hide(); }, 2000);
        $(document).on('click', '#deleteData', function (e) {
            let id = $(this).data('id');
            e.preventDefault();
            if (confirm('Are you sure delete this data!!')) {
                document.getElementById('delete-data-form-' + id).submit();
            }
        });
    </script>
</body>
</html>
```

Notes: AdminLTE **JS** (`adminlte.min.js`) and `demo.js` are dropped (the rebuilt shell no longer needs them; sidebar behavior is Alpine, loaded via `app.js`). `adminlte.min.css` stays. The `@vite` now includes `app.js` (Alpine) and is **last**.

- [ ] **Step 3: Build**

Run: `npm run build`
Expected: exit 0.

- [ ] **Step 4: Confirm `app.css` now loads after `adminlte` and Alpine loads**

Run: `grep -n 'adminlte.min.css\|@vite\|adminlte.min.js' resources/views/layouts/vendor/app.blade.php`
Expected: the `adminlte.min.css` line appears **before** the `@vite(...)` line; **no** `adminlte.min.js` line.

- [ ] **Step 5: Commit**

```bash
git add resources/views/layouts/vendor/app.blade.php resources/css/app.css
git commit -m "feat(vendor): rebuild layout shell in Tailwind+Alpine, load Tailwind last, drop AdminLTE JS"
```

---

### Task 6: Convert the dashboard page + parity verification

**Files:**
- Overwrite: `resources/views/vendor/dashboard.blade.php`

**Interfaces:**
- Consumes: `<x-ui.stat-tile>` (Task 2) and the rebuilt shell (Tasks 3–5).

- [ ] **Step 1: Overwrite the dashboard view**

Replace the entire contents of `resources/views/vendor/dashboard.blade.php` with:

```blade
@extends('layouts.vendor.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4 flex items-baseline justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">Dashboard</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-500 hover:text-slate-700">Home</a>
    </div>

    @if (auth()->user()->shop_info->name == 'null_wait')
        <a href="profile/update" class="mb-4 block rounded-lg bg-tile-danger px-4 py-3 text-center text-lg font-bold text-white">
            Please Complete Your profile.
        </a>
    @endif
    @if (auth()->user()->is_approved == 0)
        <p class="mb-4 text-center text-xl text-red-600">
            Your Account is Under Review. You Cant Upload Product At This Time
        </p>
    @endif

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-ui.stat-tile variant="info"    :value="$products"          label="Total Products"   icon="fas fa-procedures"        :href="routeHelper('product')" />
        <x-ui.stat-tile variant="warning" :value="$quantity"          label="Product Qty"      icon="fas fa-sort-numeric-down-alt" :href="routeHelper('product')" />
        <x-ui.stat-tile variant="success" :value="$orders"            label="Total Orders"     icon="fas fa-shopping-cart"     :href="routeHelper('order')" />
        <x-ui.stat-tile variant="info"    :value="$pending_orders"    label="Pending Orders"   icon="fas fa-hourglass-start"   :href="routeHelper('order/pending')" />
        <x-ui.stat-tile variant="primary" :value="$processing_orders" label="Processing Orders" icon="fas fa-running"          :href="routeHelper('order/processing')" />
        <x-ui.stat-tile variant="danger"  :value="$cancel_orders"     label="Cancel Orders"    icon="fas fa-window-close"      :href="routeHelper('order/cancel')" />
        <x-ui.stat-tile variant="primary" :value="$sihpping_order"    label="Shipping Orders"  icon="fas fa-plane"             :href="routeHelper('order/')" />
        <x-ui.stat-tile variant="danger"  :value="$refund_orders"     label="Refund Orders"    icon="fas fa-running"           :href="routeHelper('order/')" />
        <x-ui.stat-tile variant="success" :value="$delivered_orders"  label="Delivered Orders" icon="fas fa-thumbs-up"         :href="routeHelper('order/delivered')" />
        <x-ui.stat-tile variant="info"    :value="$amount"            label="Total Amount"     icon="fas fa-money-bill-alt" />
        <x-ui.stat-tile variant="warning" :value="$pending_amount"    label="Pending Amount"   icon="fas fa-money-bill-alt" />
    </div>
@endsection
```

(Preserves every metric, variant color, icon, and link target from the original.)

- [ ] **Step 2: Build + full test suite + format**

Run: `npm run build && composer test && vendor/bin/pint --test resources/views/components/ui/stat-tile.blade.php tests/Feature/Ui/StatTileComponentTest.php`
Expected: build exit 0; tests PASS; pint passed.

- [ ] **Step 3: Render the vendor dashboard and parity-check it (manual)**

Start the app and authenticate as a seeded vendor:

```bash
php artisan serve --port=8123   # (background)
```

Get a vendor login: `php artisan tinker --execute="echo \App\Models\User::whereHas('roles', fn(\$q)=>\$q->where('name','vendor'))->value('email');"` (or inspect `VendorSeeder`). Log in at `/login` (or `/login/vendor`), visit `/vendor/dashboard`.

Expected (compare against the admin dashboard reference): white sectioned sidebar with **gold** active "Dashboard" item + boxicons; collapsible Products/Profile submenus (Alpine); the 11 **solid-colored** stat tiles (teal/gold/green/blue/red) with big numbers, labels, faded corner icons, and "More info" footers; top navbar with hamburger (mobile) + gold Logout. **No AdminLTE dark sidebar.** Note any pixel drift and fix before committing.

- [ ] **Step 4: Confirm no AdminLTE classes remain in the dashboard view**

Run: `grep -nE 'small-box|content-header|content-wrapper|col-lg-3|breadcrumb' resources/views/vendor/dashboard.blade.php`
Expected: no output.

- [ ] **Step 5: Commit**

```bash
vendor/bin/pint resources/views/vendor/dashboard.blade.php
git add resources/views/vendor/dashboard.blade.php
git commit -m "feat(vendor): convert dashboard page to Tailwind stat-tiles"
```

---

## Self-Review

**Spec coverage (for the dashboard milestone slice of the vendor spec):**
- Token additions (spec §3: accent + tile-*) → Task 1. ✓
- `<x-ui.stat-tile>` (spec §4) → Task 2. ✓
- Shell rebuild: layout/sidebar/navbar in Tailwind+Alpine, app.css last, scoped reset, AdminLTE CSS kept / AdminLTE JS dropped (spec §4, §5, §6.2) → Tasks 3–5. ✓
- Dashboard view conversion + parity (spec §6.3, §7) → Task 6. ✓
- `Request::is()` active rules preserved (spec §4) → Task 3 `$navActive`. ✓
- JS-behavior replacement: pushmenu + treeview → Alpine (spec §5) → Tasks 3–5. ✓ (tabs/modal/collapse belong to later view-group plans.)
- Deferred (correct, not in this plan): other 18 views; table/select/textarea/modal/tabs/alert components; AdminLTE CSS removal; `vendor` guardrail entry — these land in plans 2..N. Noted in scope.

**Placeholder scan:** No TBD/TODO; every code step has complete file contents; the tinker/login step is a concrete manual verification, not a code placeholder. ✓

**Type/name consistency:** Token names (`--color-accent`, `--color-tile-*`) used identically in Task 1 and the `bg-accent`/`bg-tile-*` classes in Tasks 2–6. `<x-ui.stat-tile>` props (`variant/value/label/icon/href`) match between the test (Task 2), the component (Task 2), and the dashboard usage (Task 6). Alpine `sidebarOpen` scope is defined on `<body>` (Task 5) and consumed by the navbar hamburger (Task 4); `open.products`/`open.profile` are defined and consumed within `aside` (Task 3). `.dash-shell`/`.dash-sidebar` reset (Task 5) matches the classes on the layout/aside roots. ✓
