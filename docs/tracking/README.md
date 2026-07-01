# Tracking (GTM + dataLayer + Meta CAPI ready)

Production-grade, additive tracking system. GTM loads on **every** storefront page
from a single source, all ecommerce events use one consistent dataLayer schema, and
every event carries an `event_id` so a server-side layer (Meta Conversion API / server
GTM) can deduplicate against the browser hit.

GTM container: **GTM-WB39G2B7** (override per-env with `GTM_ID` in `.env`).

---

## 1. What was added / changed

**New (single source of truth):**

| File | Purpose |
| --- | --- |
| `config/tracking.php` | GTM id, currency, CAPI credentials — all env-driven |
| `resources/views/partials/gtm-head.blade.php` | GTM `<head>` loader (guarded against double-load) |
| `resources/views/partials/gtm-body.blade.php` | GTM `<noscript>` iframe |
| `resources/views/partials/tracking.blade.php` | `window.DL` helper + auto `page_view` |
| `app/Support/Tracking.php` | Server helper — deterministic `purchaseEventId()`, `orderItems()` |

**Wired into (GTM now guaranteed on every page):**

- `resources/views/layouts/frontend/app.blade.php` — head + body (covers home, cart,
  checkout, category, order-success, dynamic pages, etc.)
- `resources/views/frontend/single-product.blade.php` — standalone page, injected directly + `product_view`
- `resources/views/frontend/landing/lice-comb.blade.php` — standalone landing page
- `resources/views/frontend/landing/rust-removal.blade.php` — standalone landing page

**Events given `event_id` (Meta CAPI dedup):** `purchase`, `begin_checkout` (×4 checkout
variants), `add_to_cart`, `view_cart`. New events `page_view` + `product_view` get one
automatically from the helper.

> Admin (`layouts/admin`) and vendor (`layouts/vendor`) dashboards are intentionally
> **excluded** — internal back-office traffic should not pollute ecommerce analytics.

---

## 2. ⚠️ One required manual step — remove the old GTM to avoid duplicates

GTM was previously **not** in code; it was injected through **Admin → Settings**
(`header_code` / `body_code`, rendered in the layout). Now that GTM lives in code, you
must remove it from those settings or the container will load twice (double-counted events).

1. Admin panel → Settings → **Header Code** / **Body Code** (and **FB Pixel** if GTM was pasted there).
2. Delete any `googletagmanager.com/gtm.js` `<script>` and any `ns.html` `<noscript>` block.
3. Leave real Meta Pixel / other codes as-is.
4. Save. (The `<head>` loader also self-guards with `window.__gtmLoaded`, but removing the
   DB copy is the correct fix.)

Add to `.env` (optional overrides):

```dotenv
GTM_ID=GTM-WB39G2B7
TRACKING_CURRENCY=BDT

# Server-side (fill when the VPS endpoint is live)
META_CAPI_ENABLED=false
META_PIXEL_ID=
META_CAPI_TOKEN=
META_CAPI_TEST_CODE=
SGTM_ENDPOINT=
```

---

## 3. dataLayer helper API (`window.DL`)

Available on every page (defined in `partials/tracking.blade.php`, loaded in `<head>`).

```js
window.DL.uuid()                       // RFC4122 v4 id
window.DL.currency                     // 'BDT' (from config)
window.DL.push(event, ecommerce, extra)// low-level; auto event_id + ecommerce reset
window.DL.pageView(extra)
window.DL.productView(ecommerce, extra)
window.DL.addToCart(ecommerce, extra)
window.DL.viewCart(ecommerce, extra)
window.DL.beginCheckout(ecommerce, extra)
window.DL.purchase(ecommerce, extra)
```

- Every push automatically gets a unique `event_id` unless you pass one in `extra`.
- Before every ecommerce event the helper pushes `{ ecommerce: null }` (GA4 best practice
  so item arrays don't merge between events).
- Pass a deterministic id when browser + server must match:
  `window.DL.purchase(ec, { event_id: 'purchase.INV-123' })`.

---

## 4. Event catalog — triggers + JSON

All events use `currency: "BDT"`, GA4 `items[]` shape, and an `event_id`.

### `page_view` — every page load (auto)
```json
{
  "event": "page_view",
  "event_id": "b1c2…",
  "page_type": "product",
  "page_path": "/product/premium-lice-comb",
  "page_location": "https://site.com/product/premium-lice-comb",
  "page_title": "Premium Lice Comb"
}
```

### `product_view` — single product page (`product.details`)
```json
{
  "event": "product_view",
  "event_id": "…",
  "ecommerce": {
    "currency": "BDT",
    "value": 850,
    "items": [{ "item_id": "42", "item_name": "Premium Lice Comb", "price": 850, "quantity": 1 }]
  }
}
```

### `add_to_cart` — "Add" button (product grid / product page)
```json
{
  "event": "add_to_cart",
  "event_id": "…",
  "ecommerce": {
    "currency": "BDT",
    "value": 850,
    "items": [{ "item_id": "42", "item_name": "Premium Lice Comb", "price": 850, "quantity": 1 }]
  }
}
```

### `view_cart` — cart page load (`cart`)
```json
{
  "event": "view_cart",
  "event_id": "…",
  "ecommerce": {
    "currency": "BDT",
    "value": 1700,
    "total_quantity": 2,
    "items": [{ "item_id": "42", "item_name": "Premium Lice Comb", "price": 850, "quantity": 2 }]
  }
}
```

### `begin_checkout` — checkout page load (`checkout`, all 4 variants)
```json
{
  "event": "begin_checkout",
  "event_id": "…",
  "ecommerce": {
    "currency": "BDT",
    "value": 1700,
    "items": [{ "item_id": "42", "item_name": "Premium Lice Comb", "price": 850, "quantity": 2 }]
  }
}
```

### `purchase` — order success page (`order.success`) — **deterministic event_id**
```json
{
  "event": "purchase",
  "event_id": "purchase.INV-2026-000123",
  "ecommerce": {
    "transaction_id": "INV-2026-000123",
    "value": 1780,
    "currency": "BDT",
    "shipping": 80,
    "total_quantity": 2,
    "items": [{ "item_id": "42", "item_name": "Premium Lice Comb", "price": 850, "quantity": 2 }],
    "customer_info": { "first_name": "Rahim", "phone": "017…" }
  }
}
```

> The purchase `event_id` = `App\Support\Tracking::purchaseEventId($order)`
> (`"purchase." . invoice`). Use the **same** formula server-side so Meta dedups.

Also present (pre-existing, still firing): `add_payment_info` (checkout, on name+phone
blur) and `remove_from_cart` (cart / offcanvas).

---

## 5. GTM container setup (dashboard, one-time)

In the GTM container **GTM-WB39G2B7**:

1. **Variables → User-Defined → Data Layer Variables**: create `DLV - ecommerce`,
   `DLV - event_id`, `DLV - page_type` (and per-field as needed).
2. **GA4 Configuration tag** on *All Pages* (fires GA4 automatically).
3. **GA4 Event tags** — one per event, each with a **Custom Event** trigger matching the
   event name (`product_view`, `add_to_cart`, `view_cart`, `begin_checkout`, `purchase`).
   Map `Ecommerce Data` → *Use data layer* so `items`/`value`/`currency` flow through.
4. **Meta Pixel tags** (browser) — for each conversion event fire the matching Meta event
   and set **Event ID** = `{{DLV - event_id}}`:
   | dataLayer event | Meta event |
   | --- | --- |
   | `product_view` | `ViewContent` |
   | `add_to_cart` | `AddToCart` |
   | `begin_checkout` | `InitiateCheckout` |
   | `purchase` | `Purchase` |
5. **Preview** (Tag Assistant) → walk product → cart → checkout → success and confirm each
   event + its `event_id`. Then **Publish**.

---

## 6. Server-side tracking (Meta CAPI) — VPS step-by-step

The browser already emits a matching `event_id` per event, so the server just needs to
send the same event with the same id. Two options — pick one.

### Option A — Server GTM (sGTM) on the VPS (recommended, no app code)
1. Provision a small VPS (1 vCPU / 1 GB) with Docker.
2. Deploy Google's **server-side GTM** image; point a subdomain (`sgtm.yourdomain.com`,
   HTTPS via Caddy/Nginx + Let's Encrypt) at it. Put the sGTM container URL in `SGTM_ENDPOINT`.
3. In the **web** container, set the GA4 tag's `server_container_url` to the sGTM endpoint
   (first-party). Events now flow browser → sGTM.
4. In the **server** container add the **Meta Conversions API** tag, keyed on the same
   custom events; map `Event ID` = `{{event_id}}`, `Event Name` per the table above.
   Add `META_PIXEL_ID` + `META_CAPI_TOKEN`.
5. Because browser Pixel and CAPI share `event_id`, Meta deduplicates automatically.
   Validate in **Events Manager → Test Events** with `META_CAPI_TEST_CODE`.

### Option B — Direct CAPI from Laravel (purchase, most reliable signal)
Fire server → Meta at order creation, reusing the deterministic id.

1. `composer require guzzlehttp/guzzle` (already present in Laravel).
2. Create `app/Services/MetaCapi.php` that POSTs to
   `https://graph.facebook.com/v19.0/{META_PIXEL_ID}/events?access_token={META_CAPI_TOKEN}`:
   ```php
   'data' => [[
     'event_name'    => 'Purchase',
     'event_id'      => \App\Support\Tracking::purchaseEventId($order), // == browser id
     'event_time'    => now()->timestamp,
     'action_source' => 'website',
     'event_source_url' => route('order.success', $order->order_id),
     'user_data'     => [ // hash PII with sha256
       'ph' => hash('sha256', preg_replace('/\D/', '', $order->phone)),
       'fn' => hash('sha256', mb_strtolower($order->first_name)),
     ],
     'custom_data'   => [
       'currency' => \App\Support\Tracking::currency(),
       'value'    => (float) $order->total,
       'contents' => collect(\App\Support\Tracking::orderItems($order))
                       ->map(fn ($i) => ['id' => $i['item_id'], 'quantity' => $i['quantity'], 'item_price' => $i['price']]),
     ],
   ]]
   ```
3. Dispatch it from `OrderController::orderStore*` (queued job) **after** the order commits,
   guarded by `config('tracking.capi.enabled')`. Never block checkout on the API call.
4. Same `event_id` on the browser `purchase` push → Meta dedups the two.

**Data consistency rules (both options):** identical `event_id`, identical `value` (order
total as float), identical `currency`, and always hash user PII (phone/email/name) with
SHA-256 before sending server-side.

---

## 7. QA checklist

- [ ] Removed the old GTM snippet from Admin settings (§2).
- [ ] `view source` on home, a product, cart, checkout, order-success, and **both landing
      pages** → GTM `<script>` in head + `<noscript>` in body, exactly once each.
- [ ] GTM Preview shows: `page_view` everywhere; `product_view` on product; `add_to_cart`
      on add; `view_cart` on cart; `begin_checkout` on checkout; `purchase` on success.
- [ ] Each event has a non-empty `event_id`; `purchase.event_id` == `purchase.<invoice>`.
- [ ] GA4 Realtime + Meta Test Events receive the events; no duplicate containers in
      Tag Assistant.
