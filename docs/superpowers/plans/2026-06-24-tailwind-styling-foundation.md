# Tailwind Styling Foundation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Establish the Tailwind brand/theme token layer, make Tailwind available on every surface, and ship a core set of `<x-ui.*>` primitives — with **no visible change** to any page — so each UI surface can later be migrated off inline styles/Bootstrap/AdminLTE.

**Architecture:** Tailwind v4 CSS-first. `resources/css/app.css` is restructured to import theme + utilities **without** global Preflight (the reset stays opt-in per surface during the layer-on-top phase). Brand tokens (orange `#f85606` primary scale, per-surface fonts, status colors) live in `@theme`. Tailwind is added to the auth/vendor/user layouts **before** their legacy bundles so legacy CSS keeps winning today (zero change); the "load Tailwind last" flip happens later, per surface. Four anonymous Blade primitives are built and unit-tested with Pest.

**Tech Stack:** Laravel 11 · PHP 8.2+ · Blade · Tailwind CSS v4 (`@tailwindcss/vite`) · Vite 8 · Alpine.js · Pest.

## Global Constraints

- **No visible change** to any page on foundation merge (parity gate). Verified by per-surface visual spot-check.
- **Layer on top:** do **not** remove or reorder legacy bundles (Bootstrap, AdminLTE, storefront theme) in this plan. New Tailwind `@vite` goes **before** legacy `<link>`s so legacy wins.
- **No new inline styles or `<style>` blocks** introduced by this plan.
- Brand primary = orange **`#f85606`** (single primary across all surfaces).
- Fonts stay **per-surface** (storefront Muli, admin/vendor/user Source Sans Pro); default `--font-sans` remains Instrument Sans.
- Neutrals = Tailwind's built-in `slate`. Status tokens map to today's values.
- Before every commit: `vendor/bin/pint` (format) and `composer test` (Pest) must pass. Conventional commit messages.
- Do **not** touch `layouts/global.blade.php`'s runtime `--primary_color` setting system (default `#108b3a`); it is the legacy storefront mechanism and is out of scope here. (See Context note.)

**Context note — two brand-color systems coexist.** Legacy CSS reads a runtime,
admin-configurable `--primary_color` (injected by `layouts/global.blade.php`, default
green `#108b3a`), while the hardcoded storefront theme uses orange `#f85606`/`#ff6a00`.
This plan introduces the **Tailwind** brand token `--color-primary: #f85606` per the
approved design and leaves the legacy runtime variable untouched. Reconciling the two
(e.g. driving `--color-primary` from the runtime setting) is a deliberate later decision
made during the storefront surface migration — not here.

---

### Task 1: Restructure `app.css` — brand tokens + controlled Preflight

**Files:**
- Modify: `resources/css/app.css` (whole file)

**Interfaces:**
- Produces: Tailwind theme tokens available as utilities + CSS custom properties —
  `bg-primary`, `bg-primary-{50..950}`, `text-primary`, `bg-secondary`, `bg-success`,
  `bg-danger`, `bg-warning`, `bg-info` (and `text-*`/`border-*` equivalents), plus font
  utilities `font-store` / `font-dash`. Global Preflight is **not** emitted.

- [ ] **Step 1: Replace the file contents**

Replace the entire contents of `resources/css/app.css` with:

```css
/* Tailwind v4 — granular imports so global Preflight is OPT-IN during the
   layer-on-top migration. tailwindcss/preflight.css is intentionally NOT imported
   globally; each surface re-enables a scoped reset as it migrates. */
@layer theme, base, components, utilities;

@import "tailwindcss/theme.css" layer(theme);
@import "tailwindcss/utilities.css" layer(utilities);

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
    /* ---- Typography: per-surface; default stays Instrument Sans ---- */
    --font-sans:
        "Instrument Sans", ui-sans-serif, system-ui, sans-serif,
        "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol",
        "Noto Color Emoji";
    --font-store: "Muli", var(--font-sans);
    --font-dash: "Source Sans Pro", var(--font-sans);

    /* ---- Brand primary: storefront orange #f85606 (anchor at 500) ---- */
    --color-primary-50:  #fff3ec;
    --color-primary-100: #ffe2d1;
    --color-primary-200: #ffc4a3;
    --color-primary-300: #ff9d6b;
    --color-primary-400: #fd7233;
    --color-primary-500: #f85606;
    --color-primary-600: #e84a02;
    --color-primary-700: #c03c06;
    --color-primary-800: #98310d;
    --color-primary-900: #7c2c0f;
    --color-primary-950: #431304;
    --color-primary: #f85606; /* literal, NOT var() — a var() reference in a theme
                                 value is tree-shaken by Tailwind v4 (not counted as
                                 "used"), which would make bg-primary transparent. */

    /* ---- Secondary + status (literal values; mapped to today's colors) ---- */
    --color-secondary: #1e293b; /* slate-800 */
    --color-success:   #16a34a; /* green-600 */
    --color-danger:    #dc2626; /* red-600 */
    --color-warning:   #ffc107; /* amber */
    --color-info:      #0284c7; /* sky-600 */
}
```

- [ ] **Step 2: Build and verify it compiles**

Run: `npm run build`
Expected: exits 0, prints a built `public/build/assets/app-*.css`.

- [ ] **Step 3: Verify the brand token is in the output**

Run: `grep -rl 'f85606' public/build/assets/`
Expected: at least one `app-*.css` file is listed (the primary token compiled to `:root`).

- [ ] **Step 4: Verify global Preflight is NOT in the output**

Run: `grep -rc 'text-size-adjust' public/build/assets/*.css`
Expected: every file prints `0` (Preflight's `html { -webkit-text-size-adjust }` rule is absent → Preflight omitted).

- [ ] **Step 5: Commit**

```bash
vendor/bin/pint
git add resources/css/app.css
git commit -m "feat(css): add Tailwind brand tokens and make Preflight opt-in"
```

---

### Task 2: Add Tailwind to the auth, vendor, and user layouts

Tailwind is currently absent from these three layouts. Add `@vite(['resources/css/app.css'])`
**before** the legacy stylesheet links so legacy CSS keeps winning (no visual change). CSS
only — not `app.js` — to avoid introducing Alpine where it isn't used yet.

**Files:**
- Modify: `resources/views/auth/layouts/app.blade.php` (head, ~line 8)
- Modify: `resources/views/layouts/vendor/app.blade.php` (head, ~line 12)
- Modify: `resources/views/layouts/user/app.blade.php` (head, ~line 13)

**Interfaces:**
- Consumes: tokens/utilities from Task 1.
- Produces: Tailwind utilities available (but legacy-overridden) on the auth/vendor/user surfaces, ready for those surfaces' future migration.

- [ ] **Step 1: auth layout — insert `@vite` before the legacy links**

In `resources/views/auth/layouts/app.blade.php`, the head currently reads:

```blade
    @notifyCss
    <link rel="stylesheet" href="/assets/frontend/css/bootstrap.min.css">
```

Change it to:

```blade
    @notifyCss
    {{-- Tailwind loaded BEFORE legacy bundles → legacy wins during the layer-on-top phase. --}}
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="/assets/frontend/css/bootstrap.min.css">
```

- [ ] **Step 2: vendor layout — insert `@vite` after the global include**

In `resources/views/layouts/vendor/app.blade.php`, the head currently reads:

```blade
        @include('layouts.global')

        <!-- Google Font: Source Sans Pro -->
```

Change it to:

```blade
        @include('layouts.global')

        {{-- Tailwind loaded BEFORE adminlte → AdminLTE wins during the layer-on-top phase. --}}
        @vite(['resources/css/app.css'])

        <!-- Google Font: Source Sans Pro -->
```

- [ ] **Step 3: user layout — insert `@vite` after the global include**

In `resources/views/layouts/user/app.blade.php`, the head currently reads:

```blade
    @include('layouts.global')

    <!-- Google Font: Source Sans Pro -->
```

Change it to:

```blade
    @include('layouts.global')

    {{-- Tailwind loaded BEFORE adminlte → AdminLTE wins during the layer-on-top phase. --}}
    @vite(['resources/css/app.css'])

    <!-- Google Font: Source Sans Pro -->
```

- [ ] **Step 4: Verify each layout now references `@vite` exactly once**

Run: `grep -c "@vite(\['resources/css/app.css'\])" resources/views/auth/layouts/app.blade.php resources/views/layouts/vendor/app.blade.php resources/views/layouts/user/app.blade.php`
Expected: each file prints `1`.

- [ ] **Step 5: Build to ensure the manifest resolves the new references**

Run: `npm run build`
Expected: exits 0.

- [ ] **Step 6: Visual spot-check (manual, no-change gate)**

Run the app (`composer dev` or `php artisan serve` + `npm run dev`). Load one page per
surface: a login/register page (auth), the vendor dashboard, and a user account page.
Expected: each looks **identical** to before this task (Tailwind present but overridden by
legacy). If any shifts, the `@vite` line is in the wrong position — move it earlier (before
all legacy links) and re-check.

- [ ] **Step 7: Commit**

```bash
git add resources/views/auth/layouts/app.blade.php resources/views/layouts/vendor/app.blade.php resources/views/layouts/user/app.blade.php
git commit -m "feat(css): load Tailwind on auth/vendor/user layouts (legacy still wins)"
```

---

### Task 3: `<x-ui.button>` primitive

**Files:**
- Create: `resources/views/components/ui/button.blade.php`
- Test: `tests/Feature/Ui/ButtonComponentTest.php`

**Interfaces:**
- Produces: `<x-ui.button variant="primary|secondary|ghost|danger" size="sm|md|lg" :href="?" type="button">…</x-ui.button>`. Renders `<a>` when `href` is set, else `<button>`. Merges extra attributes/classes.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Ui/ButtonComponentTest.php`:

```php
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
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `vendor/bin/pest tests/Feature/Ui/ButtonComponentTest.php`
Expected: FAIL — component `ui.button` not found / view does not exist.

- [ ] **Step 3: Create the component**

Create `resources/views/components/ui/button.blade.php`:

```blade
@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-md font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 disabled:opacity-50 disabled:pointer-events-none';

    $variants = [
        'primary'   => 'bg-primary text-white hover:bg-primary-600',
        'secondary' => 'bg-secondary text-white hover:bg-slate-700',
        'ghost'     => 'bg-transparent text-primary hover:bg-primary-50',
        'danger'    => 'bg-danger text-white hover:opacity-90',
    ];

    $sizes = [
        'sm' => 'h-8 px-3 text-sm',
        'md' => 'h-10 px-4 text-sm',
        'lg' => 'h-12 px-6 text-base',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
```

- [ ] **Step 4: Run the test to verify it passes**

Run: `vendor/bin/pest tests/Feature/Ui/ButtonComponentTest.php`
Expected: PASS (5 passed).

- [ ] **Step 5: Commit**

```bash
vendor/bin/pint
git add resources/views/components/ui/button.blade.php tests/Feature/Ui/ButtonComponentTest.php
git commit -m "feat(ui): add x-ui.button primitive"
```

---

### Task 4: `<x-ui.card>` primitive

**Files:**
- Create: `resources/views/components/ui/card.blade.php`
- Test: `tests/Feature/Ui/CardComponentTest.php`

**Interfaces:**
- Produces: `<x-ui.card>` with optional `header` and `footer` slots and a default body slot.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Ui/CardComponentTest.php`:

```php
<?php

use Illuminate\Support\Facades\Blade;

it('renders body content in a bordered card', function () {
    $html = Blade::render('<x-ui.card>Body here</x-ui.card>');

    expect($html)->toContain('Body here')
        ->toContain('rounded-lg')
        ->toContain('border-slate-200');
});

it('renders a header slot when provided', function () {
    $html = Blade::render('<x-ui.card><x-slot:header>Title</x-slot:header>Body</x-ui.card>');

    expect($html)->toContain('Title')
        ->toContain('border-b')
        ->toContain('Body');
});

it('renders a footer slot when provided', function () {
    $html = Blade::render('<x-ui.card>Body<x-slot:footer>Foot</x-slot:footer></x-ui.card>');

    expect($html)->toContain('Foot')->toContain('border-t');
});
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `vendor/bin/pest tests/Feature/Ui/CardComponentTest.php`
Expected: FAIL — `ui.card` view does not exist.

- [ ] **Step 3: Create the component**

Create `resources/views/components/ui/card.blade.php`:

```blade
@props(['header' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-slate-200 bg-white shadow-sm']) }}>
    @isset($header)
        <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">{{ $header }}</div>
    @endisset

    <div class="p-4">{{ $slot }}</div>

    @isset($footer)
        <div class="border-t border-slate-200 px-4 py-3">{{ $footer }}</div>
    @endisset
</div>
```

- [ ] **Step 4: Run the test to verify it passes**

Run: `vendor/bin/pest tests/Feature/Ui/CardComponentTest.php`
Expected: PASS (3 passed).

- [ ] **Step 5: Commit**

```bash
vendor/bin/pint
git add resources/views/components/ui/card.blade.php tests/Feature/Ui/CardComponentTest.php
git commit -m "feat(ui): add x-ui.card primitive"
```

---

### Task 5: `<x-ui.badge>` primitive

**Files:**
- Create: `resources/views/components/ui/badge.blade.php`
- Test: `tests/Feature/Ui/BadgeComponentTest.php`

**Interfaces:**
- Produces: `<x-ui.badge variant="neutral|primary|success|danger|warning|info">…</x-ui.badge>`.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Ui/BadgeComponentTest.php`:

```php
<?php

use Illuminate\Support\Facades\Blade;

it('renders a neutral badge by default', function () {
    $html = Blade::render('<x-ui.badge>New</x-ui.badge>');

    expect($html)->toContain('New')
        ->toContain('rounded-full')
        ->toContain('bg-slate-100');
});

it('renders the success variant with status token', function () {
    $html = Blade::render('<x-ui.badge variant="success">Paid</x-ui.badge>');

    expect($html)->toContain('text-success');
});

it('renders the danger variant with status token', function () {
    $html = Blade::render('<x-ui.badge variant="danger">Failed</x-ui.badge>');

    expect($html)->toContain('text-danger');
});
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `vendor/bin/pest tests/Feature/Ui/BadgeComponentTest.php`
Expected: FAIL — `ui.badge` view does not exist.

- [ ] **Step 3: Create the component**

Create `resources/views/components/ui/badge.blade.php`:

```blade
@props(['variant' => 'neutral'])

@php
    $base = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium';

    $variants = [
        'neutral' => 'bg-slate-100 text-slate-700',
        'primary' => 'bg-primary/10 text-primary',
        'success' => 'bg-success/10 text-success',
        'danger'  => 'bg-danger/10 text-danger',
        'warning' => 'bg-warning/10 text-warning',
        'info'    => 'bg-info/10 text-info',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['neutral']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
```

- [ ] **Step 4: Run the test to verify it passes**

Run: `vendor/bin/pest tests/Feature/Ui/BadgeComponentTest.php`
Expected: PASS (3 passed).

- [ ] **Step 5: Commit**

```bash
vendor/bin/pint
git add resources/views/components/ui/badge.blade.php tests/Feature/Ui/BadgeComponentTest.php
git commit -m "feat(ui): add x-ui.badge primitive"
```

---

### Task 6: `<x-ui.input>` primitive

**Files:**
- Create: `resources/views/components/ui/input.blade.php`
- Test: `tests/Feature/Ui/InputComponentTest.php`

**Interfaces:**
- Produces: `<x-ui.input name="email" label="Email" type="text" :value="?" />`. Renders a
  label (when given), an input pre-filled from `old()`, and a validation error message when
  the field has an error. Safe to render without a shared error bag.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/Ui/InputComponentTest.php`:

```php
<?php

use Illuminate\Support\Facades\Blade;

it('renders a labelled input with the field name', function () {
    $html = Blade::render('<x-ui.input name="email" label="Email address" />');

    expect($html)->toContain('name="email"')
        ->toContain('Email address')
        ->toContain('rounded-md');
});

it('renders without a label when none is given', function () {
    $html = Blade::render('<x-ui.input name="phone" />');

    expect($html)->toContain('name="phone"')
        ->not->toContain('<label');
});

it('uses the provided default value', function () {
    $html = Blade::render('<x-ui.input name="city" value="Dhaka" />');

    expect($html)->toContain('value="Dhaka"');
});
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `vendor/bin/pest tests/Feature/Ui/InputComponentTest.php`
Expected: FAIL — `ui.input` view does not exist.

- [ ] **Step 3: Create the component**

Create `resources/views/components/ui/input.blade.php`:

```blade
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
])

@php
    $hasError = isset($errors) && $errors->has($name);
    $control = 'block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary '
        .($hasError ? 'border-danger' : 'border-slate-300');
@endphp

<div class="space-y-1">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $label }}</label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => $control]) }}
    />

    @if ($hasError)
        <p class="text-sm text-danger">{{ $errors->first($name) }}</p>
    @endif
</div>
```

- [ ] **Step 4: Run the test to verify it passes**

Run: `vendor/bin/pest tests/Feature/Ui/InputComponentTest.php`
Expected: PASS (3 passed).

- [ ] **Step 5: Commit**

```bash
vendor/bin/pint
git add resources/views/components/ui/input.blade.php tests/Feature/Ui/InputComponentTest.php
git commit -m "feat(ui): add x-ui.input primitive"
```

---

### Task 7: Migration guardrail test

A Pest test that fails if a **migrated** view directory regresses to inline `style="`/`<style>`.
Starts with an empty migrated-dir list (passes trivially); each future surface spec appends
its directory.

**Files:**
- Create: `tests/Feature/StyleGuardrailTest.php`

**Interfaces:**
- Produces: a CI gate keyed off the `$migratedDirs` array (relative to `resources/views/`).

- [ ] **Step 1: Write the test**

Create `tests/Feature/StyleGuardrailTest.php`:

```php
<?php

// Fails when a migrated view directory regresses to inline styling.
// As each surface is migrated, add its directory (relative to resources/views) here.
$migratedDirs = [
    // 'auth',
];

it('keeps migrated view directories free of inline styles and <style> blocks', function () use ($migratedDirs) {
    $offenders = [];

    foreach ($migratedDirs as $dir) {
        $base = resource_path('views/'.$dir);
        if (! is_dir($base)) {
            continue;
        }

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
        foreach ($files as $file) {
            if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }
            if (preg_match('/style="|<style/i', file_get_contents($file->getPathname()))) {
                $offenders[] = $file->getPathname();
            }
        }
    }

    expect($offenders)->toBeEmpty();
});
```

- [ ] **Step 2: Run the test to verify it passes (empty list)**

Run: `vendor/bin/pest tests/Feature/StyleGuardrailTest.php`
Expected: PASS (1 passed).

- [ ] **Step 3: Commit**

```bash
git add tests/Feature/StyleGuardrailTest.php
git commit -m "test(css): add inline-style guardrail for migrated surfaces"
```

---

### Task 8: Foundation verification & parity sign-off

**Files:** none (verification only).

- [ ] **Step 1: Format check**

Run: `vendor/bin/pint --test`
Expected: no formatting issues (or run `vendor/bin/pint` and commit if any).

- [ ] **Step 2: Full test suite**

Run: `composer test`
Expected: PASS — including the new `tests/Feature/Ui/*` and `StyleGuardrailTest`.

- [ ] **Step 3: Production build**

Run: `npm run build`
Expected: exits 0; `public/build/assets/app-*.css` regenerated.

- [ ] **Step 4: Cross-surface visual parity spot-check (manual)**

Run the app. Load one representative page on **each** surface: storefront home/product,
admin dashboard, vendor dashboard, a user account page, and a login page. Compare against
`main`/pre-change.
Expected: **no visible change** on any surface. Note anything that shifts; if a shift traces
to Preflight removal on storefront/admin (Task 1), add a scoped legacy override and re-verify
before sign-off. **Also trigger a flash/toast notification on the vendor, user, and auth
surfaces** — laravel-notify's toast markup uses Tailwind-named classes
(`fixed inset-0 flex items-end justify-center`), which now resolve once Tailwind utilities load
there. Confirm the toast still looks correct (likely improved); adjust if it regresses.

- [ ] **Step 5: Confirm tokens resolve where Tailwind wins (manual)**

On a page where you can add a throwaway element (or via devtools), confirm a `bg-primary`
element renders orange `#f85606` in a context where Tailwind loads last (none in foundation —
this is a forward check). Otherwise confirm `getComputedStyle(document.documentElement)
.getPropertyValue('--color-primary-500')` returns `#f85606` in the browser console.
Expected: `--color-primary-500` = `#f85606`.

- [ ] **Step 6: Final wrap-up commit (if any pint/doc changes pending)**

```bash
git status   # should be clean if previous tasks committed everything
```

---

## Self-Review

**Spec coverage:**
- Token layer (spec §3) → Task 1. ✓ (primary scale, secondary, status, per-surface fonts)
- Controlled Preflight / collision strategy (spec §4) → Task 1 (granular import, no global Preflight) + Global Constraints (Tailwind before legacy). ✓
- Tailwind available on all surfaces (spec §6.3) → Task 2 (auth/vendor/user); storefront/admin already load it. ✓
- Core `<x-ui.*>` primitives (spec §5) → Tasks 3–6 (button, card, badge, input). ✓
- Migration playbook guardrail (spec §7) → Task 7. ✓
- Verification (spec §8) → Task 8 + per-task build/grep checks. ✓
- Deferred items (migrate views, flip load order, remove legacy bundles) → correctly **not** in this plan. ✓
- Runtime `--primary_color` system → flagged in Context note, left untouched. ✓

**Placeholder scan:** No TBD/TODO; every code step contains full file contents; the
`$migratedDirs` empty array is intentional and documented, not a placeholder. ✓

**Type/name consistency:** Token names (`--color-primary*`, `--color-secondary`,
`--color-{success,danger,warning,info}`, `--font-store`, `--font-dash`) are used identically
in `app.css` and in the component class strings (`bg-primary`, `bg-secondary`, `bg-danger`,
`text-success`, etc.). Component tags (`x-ui.button/card/badge/input`) match their file paths
(`components/ui/{button,card,badge,input}.blade.php`) and test render strings. ✓
