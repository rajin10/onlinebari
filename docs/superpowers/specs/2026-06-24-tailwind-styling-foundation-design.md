# Tailwind Styling Refactor — Foundation Design

**Date:** 2026-06-24
**Status:** Design (approved decisions captured below; awaiting spec review)
**Scope of this doc:** The **foundation** only — the brand/theme token layer, the
`app.css` restructure, Tailwind made available on every surface, a core set of shared
Blade UI primitives, and the migration playbook every surface follows. **No views are
migrated by this spec.** Each UI surface is migrated later as its own spec → plan →
implement cycle.

---

## 1. Goal & guiding decisions

Refactor the app's styling so **Tailwind CSS is the primary styling system**, replacing
inline `style=""` attributes and `<style>` blocks. Direction confirmed with the user:

| Decision | Choice |
|---|---|
| Migration intent | **Migrate + light cleanup** — parity by default, unify obvious inconsistencies into tokens |
| Brand source | **Extract from current code** (no external Figma/brand guide) |
| Legacy frameworks | **Layer Tailwind on top** — keep Bootstrap/AdminLTE/theme bundles loaded for un-migrated views; remove each per-surface once that surface is fully converted |
| Coverage | **All surfaces**, sequenced (not big-bang) |
| Brand primary | **One primary everywhere** — storefront orange `#f85606` |
| Brand font | **Keep current fonts per surface** (storefront Muli, admin Source Sans Pro) — held as per-surface tokens |
| UI primitives | **Build a core set in the foundation** (`<x-ui.button/card/badge/input>`) |
| Surface order | **auth → vendor → user → admin → storefront** |

"Tailwind primary" + "layer on top" reconcile as: legacy bundles stay loaded for
un-migrated views, but Tailwind is the **only** way we author/refactor markup (no new
inline styles or `<style>` blocks), and each legacy bundle is dropped once its surface is
fully converted. End state: Tailwind-primary; path: incremental and reversible.

---

## 2. Current-state audit (why the design is shaped this way)

- **Tailwind v4** is installed (CSS-first, `@import "tailwindcss"` in
  `resources/css/app.css`) but barely used: the only `@theme` token is `--font-sans:
  Instrument Sans`; **no color tokens**, so `bg-primary`/`text-primary`/`bg-secondary`
  (~17 uses) generate nothing in pure-Tailwind contexts.
- **245 Blade files**; **713** inline `style=""` occurrences across **136** files; **114**
  `<style>` blocks.
- **Three legacy stacks coexist:** storefront on Bootstrap + a purchased theme
  (`public/assets/frontend/css/` — `style.css`/`unministy.css`, slick, flexslider,
  jquery-ui); admin/vendor/user on **AdminLTE**; auth on Bootstrap + storefront theme.
  Plus many third-party JS-widget stylesheets (select2, summernote, sweetalert2, chart.js,
  fullcalendar, daterangepicker, dropzone…).
- **Brand drift:** storefront orange `#f85606`/`#ff6a00`; admin purple `#667eea`
  (blade gradients); new dashboard gold `#f2d231` + Tailwind **slate** grays + light/dark
  CSS vars; stray teal `#1ca4b9`, blue `#007bc4`. Colors like `#3f3d56`/`#2f2e41` are
  undraw illustration SVGs — **not** brand, must not be enshrined.

**Critical load-order finding** (drives the Preflight strategy):

| Layout | Loads `app.css` (Tailwind)? | Order vs legacy |
|---|---|---|
| `layouts/frontend/app.blade.php` | Yes | Tailwind **before** legacy theme → legacy overrides Tailwind |
| `layouts/admin/app.blade.php` | Yes | `dashboard-assets` → Tailwind → **adminlte.css last** → AdminLTE overrides Tailwind |
| `layouts/vendor/app.blade.php` | **No** | No Tailwind at all today |
| `layouts/user/app.blade.php` | **No** | No Tailwind at all today |
| `auth/layouts/app.blade.php` | **No** | No Tailwind at all today |

Implication: the lever that makes Tailwind "primary" is **load order** (app.css must load
*last* in a layout for its utilities to win). The main risk is **Preflight** — Tailwind's
global base reset — altering un-migrated Bootstrap/AdminLTE markup when Tailwind moves last
or is newly added.

---

## 3. Token layer (`resources/css/app.css`, `@theme`)

### 3.1 Colors

- **Primary** — orange brand, base `#f85606` plus a tonal scale so both `bg-primary` and
  `bg-primary-600` resolve. Proposed (tunable during implementation):

  ```
  --color-primary-50:  #fff3ec;
  --color-primary-100: #ffe2d1;
  --color-primary-200: #ffc4a3;
  --color-primary-300: #ff9d6b;
  --color-primary-400: #fd7233;
  --color-primary-500: #f85606;   /* brand anchor */
  --color-primary-600: #e84a02;
  --color-primary-700: #c03c06;
  --color-primary-800: #98310d;
  --color-primary-900: #7c2c0f;
  --color-primary-950: #431304;
  --color-primary:     var(--color-primary-500);  /* bare bg-primary = brand orange */
  ```

- **Neutrals** — use Tailwind v4's built-in `slate` scale (already used by the new
  dashboard). No custom neutral tokens.
- **Secondary** — dark slate for headings/footers: `--color-secondary: var(--color-slate-800)`.
- **Status (semantic aliases mapped to today's values):**
  ```
  --color-success: #16a34a;   /* green-600, matches dashboard */
  --color-danger:  var(--color-red-600);
  --color-warning: #ffc107;   /* amber, matches storefront */
  --color-info:    var(--color-sky-600);
  ```

### 3.2 Typography (per-surface, kept as-is)

Fonts are **not** unified. Keep a neutral default and per-surface font tokens, applied at
each layout root:

```
--font-sans:  "Instrument Sans", ui-sans-serif, system-ui, sans-serif, …;  /* default */
--font-store: "Muli", var(--font-sans);                 /* storefront + auth */
--font-dash:  "Source Sans Pro", var(--font-sans);      /* admin / vendor / user */
```

Applied via a utility (`font-store` / `font-dash`) on the layout's root element during that
surface's migration — not globally, so no surface's type changes until it is migrated.

### 3.3 Spacing / radius / shadow

Keep Tailwind v4 defaults. Add brand tokens only where a concrete brand value exists
(none identified yet). Avoid premature tokens.

---

## 4. Collision & Preflight strategy (key technical decision)

**Chosen:** no class prefix + **load-order convention** + **controlled Preflight**.

- **No prefix.** Class names stay clean (`flex`, not `tw-flex`). Collisions are avoided by
  *not mixing* Tailwind and legacy classes on the same element and by load order.
- **Load-order convention.** A layout's `app.css` must load **last** for Tailwind to win.
  The foundation does **not** flip order on storefront/admin (deferred to their specs to
  keep foundation zero-change); it establishes the rule and the plumbing.
- **Controlled Preflight.** Split the Tailwind import so the global base reset is opt-in:

  ```css
  @layer theme, base, components, utilities;
  @import "tailwindcss/theme.css"     layer(theme);
  @import "tailwindcss/utilities.css" layer(utilities);
  /* preflight intentionally NOT imported globally during the layering phase */
  ```

  Foundation ships **without** global Preflight, so adding Tailwind to a legacy layout adds
  only (unused) utilities + tokens and **cannot reset** Bootstrap/AdminLTE markup. Each
  surface re-introduces a **scoped reset** when it migrates; a surface that fully drops its
  legacy bundle can opt back into full Preflight at the end.

- Disable Tailwind's `container` utility (or simply don't use it) to avoid clashing with
  Bootstrap/AdminLTE `.container` semantics; use `max-w-* mx-auto` instead.

**Rejected:** a `tw-` prefix — zero collisions but verbose, ugly, and contradicts the
"Tailwind primary / clean markup" goal.

---

## 5. Shared UI primitives (built in foundation)

A small, token-driven set under `resources/views/components/ui/`, used only by migrated
markup (so they don't affect un-migrated pages until placed):

- `<x-ui.button>` — variants `primary | secondary | ghost | danger`, sizes `sm | md | lg`,
  optional `:href` (renders `<a>` vs `<button>`). Uses `primary`/status tokens.
- `<x-ui.card>` — container with header/body/footer slots.
- `<x-ui.badge>` — status colors via tokens.
- `<x-ui.input>` — label + control + error slot; pairs with Laravel validation/old().

Each primitive: one clear purpose, documented props, no dependency on legacy frameworks.
APIs may be extended (not rewritten) as surfaces reveal needs.

---

## 6. What this spec ships vs defers

**Ships (foundation):**
1. `@theme` token layer (§3).
2. `app.css` restructured for controlled Preflight (§4).
3. `@vite(['resources/css/app.css', …])` added to the **vendor / user / auth** layouts
   (Preflight-off → only unused utilities → no visual change).
4. Core `<x-ui.*>` primitives (§5).
5. This playbook + an optional CI grep guardrail (§7).

**Defers (per-surface specs):**
- Migrating any view's inline styles / `<style>` blocks.
- Flipping `app.css` to load last on storefront/admin.
- Removing any legacy bundle.
- Re-enabling full Preflight per surface.

**Foundation acceptance = no visible change** on any page after merge.

---

## 7. Migration playbook (every surface spec follows this)

1. In the surface layout: move `@vite(app.css)` to load **last**; add a **scoped reset**
   for the migrated regions; set the surface font token (`font-store` / `font-dash`) on the
   root.
2. Per view: replace inline `style=""` and `<style>` blocks with token-based Tailwind
   utilities; reuse `<x-ui.*>` primitives; **never** add new inline styles or `<style>`.
3. Migrate a component **fully** — never mix Bootstrap `row/col/container` with Tailwind on
   the same element.
4. When the surface no longer references its legacy bundle, **remove that bundle** from the
   layout and verify.
5. Third-party JS widgets (select2, summernote, sweetalert2, chart.js, fullcalendar, …)
   keep their own CSS for now; optionally re-themed later. They are out of scope for
   "replace manual styling."

**Guardrail (optional, recommended):** a CI/grep check that fails on new `style="` or
`<style` introduced under a directory already marked migrated.

**Surface order & rough size:** auth (17) → vendor (32) → user → admin (117) →
storefront (74, last — most brand-sensitive, Bootstrap + purchased theme).

---

## 8. Verification

- **Foundation:** visual spot-check of one representative page per surface
  (storefront/admin/vendor/user/auth) before vs after merge — expect **no change**. Run
  `npm run build` clean; `composer test` green; `vendor/bin/pint`.
- **Each surface (later):** before/after visual parity on its key pages; confirm legacy
  bundle removed only when nothing references it; guardrail green.

---

## 9. Risks & mitigations

| Risk | Mitigation |
|---|---|
| Preflight resets break un-migrated legacy pages | Global Preflight omitted during layering; scoped per surface (§4) |
| Removing global Preflight changes storefront/admin (which load it today) | Low risk — their legacy bundles load *after* `app.css` and already override most of Preflight; the §8 spot-check is the gate, and any residual shift is fixed before merge rather than assumed away |
| Load-order flip silently changes existing Tailwind-using pages (admin/storefront) | Deferred out of foundation; handled inside each surface spec with visual parity checks |
| `.container`/`.bg-primary`/`.shadow` collisions | No-mix rule (§4/§7); disable Tailwind `container`; Tailwind loads last only on migrated surfaces |
| Enshrining non-brand colors (undraw SVGs `#3f3d56`/`#2f2e41`) | Token palette is the single source; illustration colors stay inline in their SVGs, never tokenized |
| Per-surface fonts add complexity | Tokens (`font-store`/`font-dash`) applied at layout root only; default `--font-sans` unchanged |
| Scope creep into widget replacement | Widgets explicitly out of scope (§7) |
| New semantic tokens (`bg-info`/`bg-primary`/…) collide with same-named Bootstrap/AdminLTE classes on surfaces where Tailwind loads last (storefront) | **Verified neutral at foundation time:** Bootstrap's `.bg-*`/`.text-*`/`.border-*` utilities are all `!important`, so they beat Tailwind's non-important utilities regardless of load order — no color shift. **Forward note:** this same `!important` means the storefront migration cannot rely on Tailwind "winning" by load order; it must remove Bootstrap's utilities (or use Tailwind's `!` important modifier / scoping) as it converts each component. |

---

## 10. Decomposition / next specs

This foundation unblocks five surface specs, each its own spec → plan → implement cycle, in
order: **auth → vendor → user → admin → storefront**. Each reuses the tokens, primitives,
and playbook defined here.
