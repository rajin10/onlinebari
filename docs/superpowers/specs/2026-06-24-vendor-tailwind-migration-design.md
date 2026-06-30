# Vendor Surface — Tailwind Migration Design

**Date:** 2026-06-24
**Status:** Design (decisions captured; awaiting spec review)
**Depends on:** [Foundation design](2026-06-24-tailwind-styling-foundation-design.md) (tokens, primitives, playbook)
**Scope:** Migrate the **vendor** surface (19 views + shell) to Tailwind, **dropping AdminLTE**, while **preserving the current visual look exactly**. This is the first real surface migration (pilot) of the program.

> **OVERRIDING PRINCIPLE (applies to the whole refactor):** Everything must look **exactly the same** as today — only the styling mechanism changes (rebuilt in TailwindCSS). This is a rebuild to visual parity, **never a redesign**. Any pixel difference from the current UI is a defect to fix, not an improvement.

---

## 1. Goal & reconciled decisions

Rebuild the vendor dashboard in **Tailwind**, removing AdminLTE, so that it looks **identical to today's dashboard** (the existing admin dashboard is the canonical reference — see the live site `/admin/dashboard`). This is a **mechanism rebuild to visual parity**, not a redesign.

| Decision | Choice | Notes |
|---|---|---|
| Depth | **Full conversion** — drop AdminLTE from vendor | Confirmed |
| Look | **Match today exactly** (Tailwind rebuild to visual parity) | Confirmed; an earlier orange/slate redesign mockup was rejected |
| Dashboard accent | **Gold `#f2d231`** (matches existing admin) | Relaxes the foundation's "one primary everywhere" — storefront stays orange, **dashboards use gold** |
| JS widgets | DataTables, select2, summernote, dropzone **stay** (own CSS/JS) | Out of scope, per foundation |
| AdminLTE/Bootstrap JS behaviors | Rebuilt in **Alpine** | treeview, pushmenu, tabs, one modal, one collapse, button-groups |

**Conflict resolved:** "keep previous UI looks" + "drop AdminLTE" are both satisfied by rebuilding the *same look* in Tailwind. Parity is verified against the live admin dashboard, not assumed.

---

## 2. Current-state reference (what "the look" is)

- **Sidebar** (canonical = admin's `layouts/admin/sidebar.blade.php` + `public/dashboard-assets/style.css`):
  translucent-white panel (`rgba(255,255,255,.85)`), a profile header card, Profile/Logout
  buttons, **sectioned nav** (HOME / ORDERS / CATALOG … with `<label>` + chevron), boxicons
  (`bx bx-*`), list items `color:#444`, **hover/active = gold `#f2d231`, text `#000`**.
- **Stat tiles** (admin dashboard content): **AdminLTE `small-box`** — solid color, white text,
  big number, label, large faded corner icon, darker "More info" footer bar. Colors:
  info `#17a2b8` (teal), warning `#ffc107` (gold), success `#28a745` (green),
  primary `#007bff` (blue), danger `#dc3545` (red).
- **Page bg** `#f0f2f5`; content uses Bootstrap grid + `.card` + `.table` + form controls.
- The vendor surface today is still **pure AdminLTE** (its own `layouts/vendor/app.blade.php`),
  so it does NOT yet look like the admin reference. Migrating vendor = make it look like the
  admin reference, in Tailwind.

**Vendor inventory:** 19 views — `dashboard`; `order/{index,pending,processing,delivered,cancel,create,show,invoice}`; `product/{index,active,disable,form,show}`; `profile/{index,update,password-change}`; `withdraw`, `withdraw-list`. Class usage: ~185 grid, 87 `form-control`, 73 `btn`, 61 `card`, 33 `badge`, 20 `table`, 20 `small-box`, 14 `modal`. JS: DataTables (heavy), select2, summernote, dropzone.

---

## 3. Token additions (`@theme` in `app.css`)

Add a **dashboard accent** and the **stat-tile solid colors** (so the look is token-driven, not magic hex):

```
--color-accent:       #f2d231;  /* dashboard gold (sidebar active/hover) */
--color-accent-fg:    #000000;  /* text on gold */

/* stat-tile solids — match AdminLTE small-box */
--color-tile-info:    #17a2b8;
--color-tile-warning: #ffc107;
--color-tile-success: #28a745;
--color-tile-primary: #007bff;
--color-tile-danger:  #dc3545;
```

`--color-primary` (orange) is unchanged and remains the storefront/`<x-ui.button>` default. (The dashboards deliberately use `accent`/`tile-*`, not `primary`.)

---

## 4. Components (Tailwind + Alpine) — built to match, shared with admin later

Created under `resources/views/components/`, TDD'd like the foundation primitives. Designed to be reused by the **admin** migration, so they encode the canonical dashboard look once.

- `<x-dash.layout>` — the dashboard shell wrapper (replaces `layouts/vendor/app`'s AdminLTE body): a Tailwind flex layout (sidebar + main column), Alpine `x-data` for sidebar open/close (mobile drawer + desktop collapse via the navbar hamburger). Loads `app.css` **last**; no AdminLTE.
- `<x-dash.sidebar>` — translucent-white sectioned nav matching dashboard-assets: profile card, account actions, `<x-dash.nav-section>` + `<x-dash.nav-item>` with gold active/hover (`bg-accent text-accent-fg`), boxicons, collapsible sections via Alpine. Accepts the menu definition from the vendor partial (preserving every `Request::is()` active rule).
- `<x-dash.navbar>` — top bar: Alpine hamburger (toggles sidebar), Visit Site, logout.
- `<x-ui.stat-tile>` — solid small-box: `variant=info|warning|success|primary|danger`, `:value`, `label`, `icon`, optional `:href` (renders the "More info" footer). Uses `--color-tile-*`.
- `<x-ui.table>` — bordered/striped table matching current; plays nice with DataTables (DataTables enhances the same `<table>`; we style the wrapper/controls with Tailwind, keep DataTables' JS).
- `<x-ui.select>`, `<x-ui.textarea>` — form controls matching current (pair with select2/summernote, which enhance them).
- `<x-ui.modal>` (Alpine), `<x-ui.tabs>` (Alpine), `<x-ui.alert>`.

Reuse foundation's `<x-ui.button/card/badge/input>` (restyled if needed to match the dashboard look).

---

## 5. JS behavior replacements (AdminLTE/Bootstrap → Alpine)

| Today (data-widget / data-toggle) | Replacement |
|---|---|
| `pushmenu` (sidebar toggle) | Alpine state on `<x-dash.layout>` |
| `treeview` (collapsible submenus) | Alpine per `<x-dash.nav-section>` |
| `toggle="tab"` (7) | `<x-ui.tabs>` (Alpine) |
| modal + `dismiss="modal"` (product/form) | `<x-ui.modal>` (Alpine) |
| `toggle="collapse"` (1) | Alpine `x-show` |
| `toggle="buttons"` (2) | native radios / Alpine |

**Kept:** jQuery (AJAX, the `#deleteData` confirm, notify hide), DataTables/select2/summernote/dropzone init scripts. **Removed from the vendor layout:** `adminlte.min.css`, `adminlte.min.js`, `demo.js`, the empty control-sidebar + fullscreen widgets. **Bootstrap bundle JS** stays only if a kept widget needs it; otherwise removed (verified per page).

---

## 6. Migration sequence (becomes the plan's task groups)

1. **Tokens** (§3) + **shared dashboard components/primitives** (§4), TDD.
2. **Shell**: `<x-dash.*>` layout/sidebar/navbar; new `layouts/vendor/app` using them; `app.css` last + scoped reset; AdminLTE removed from the vendor layout.
3. **Dashboard** view (stat tiles) — the first end-to-end parity check.
4. **product/** (index, active, disable, form [heaviest], show).
5. **order/** (index, pending, processing, delivered, cancel, create, show, invoice).
6. **profile/** (index, update, password-change) + **withdraw**, **withdraw-list**.
7. **Close-out**: confirm no vendor view references AdminLTE; add `vendor` to the guardrail; full parity verification.

---

## 7. Verification

- **Functional:** every vendor route returns 200 and every action works (authenticated render walk against the seeded local DB).
- **Visual parity:** before/after each page vs the **live admin look** / the provided screenshot — sidebar, tiles, tables, forms, modals must match. The highest-risk areas are the JS widgets without Bootstrap (DataTables wrapper, select2, summernote) — style their wrappers with Tailwind to match.
- **Guardrail/tests:** new components have Pest render tests; `vendor` added to `StyleGuardrailTest`; `composer test` green; `npm run build` clean; `vendor/bin/pint`.

---

## 8. Risks

| Risk | Mitigation |
|---|---|
| Tailwind rebuild drifts from the current look | Verify each page before/after against the live reference; tokens encode exact colors (gold, tile solids) |
| DataTables/select2/summernote look wrong without Bootstrap/AdminLTE CSS | Keep their own CSS; style wrappers/controls with Tailwind to match; spot-check each widget |
| Sidebar/menu active states regress | Port every `Request::is()` rule verbatim into `<x-dash.nav-item>` |
| Flipping `app.css` to load last changes other vendor-rendered markup | Vendor layout is vendor-only; scoped reset; parity walk catches issues |
| Bootstrap bundle JS removal breaks a hidden behavior | Remove only after confirming kept widgets don't need it; per-page functional walk |
| Gold-accent dashboards diverge from orange brand | Intentional, per "keep previous UI looks"; documented in §1 |

---

## 9. Out of scope

Email templates; the broken `layouts.app` views (pre-existing); admin/user/storefront/auth surfaces (own cycles). Admin migration later reuses the `<x-dash.*>` + `<x-ui.*>` components built here.
