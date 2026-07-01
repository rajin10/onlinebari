# Browser Pixel + Meta Conversions API — step-by-step (Hostinger VPS)

This is the exact checklist to go from "dataLayer fires" to "GA4 + Meta Pixel
(browser) + Meta Conversions API (server) all working and deduplicated".

Your app already: loads GTM on every page, pushes all events with a matching
`event_id`, and ships a ready server-side Purchase sender
(`app/Services/MetaCapiService.php` + `app/Jobs/SendMetaCapiEvent.php`) wired into
`OrderController::orderSuccess()`. It stays a **no-op until you enable it** in `.env`.

---

## Architecture (what runs where)

```
Browser ── dataLayer ──► GTM (GTM-WB39G2B7) ──► GA4 tags
                                            └──► Meta Pixel (browser)   ┐
                                                                        ├─ same event_id ⇒ Meta dedupes
Laravel (VPS) ── MetaCapiService ──► Meta Conversions API (server) ─────┘
```

**Recommendation for a Hostinger VPS + Laravel shop:** use **direct Laravel CAPI**
(already built) for the server side — no extra server, no Docker, lowest cost, and the
money-event (Purchase) is the most reliable. Only set up **server-side GTM (sGTM)** later
if you want *all* events server-routed with first-party cookies (Part 6, optional).

---

## Part 1 — Meta setup (get Pixel ID + CAPI token)

1. **Events Manager** → https://business.facebook.com/events_manager2 → select (or create)
   your **Pixel / Dataset**. Copy the **Dataset (Pixel) ID** — a number like `123456789012345`.
2. In the dataset: **Settings → Conversions API → Generate access token**. Copy the long token.
   (Keep it secret — it is a credential.)
3. (Optional, for testing) **Test Events** tab → copy the **Test Event Code** (e.g. `TEST12345`).

---

## Part 2 — Configure the app (`.env` on the VPS)

Add these to the production `.env` (never commit real values):

```dotenv
GTM_ID=GTM-WB39G2B7
TRACKING_CURRENCY=BDT

META_CAPI_ENABLED=true
META_PIXEL_ID=123456789012345
META_CAPI_TOKEN=EAAG...your-long-token...
# Only while testing — REMOVE this line once verified in Test Events:
META_CAPI_TEST_CODE=TEST12345
```

Then rebuild the config cache (Part 4). With `META_CAPI_ENABLED=false` (or token missing),
the server sender does nothing — safe default.

---

## Part 3 — GTM dashboard (browser tags + dedup)

Open the container **GTM-WB39G2B7** → *Workspace*.

### 3a. Variables (Data Layer Variables)
**Variables → New → Data Layer Variable** — create these (Version 2):

| Variable name | Data Layer Variable Name |
| --- | --- |
| `DLV - event_id` | `event_id` |
| `DLV - ecommerce.value` | `ecommerce.value` |
| `DLV - ecommerce.currency` | `ecommerce.currency` |

### 3b. Triggers (Custom Event, one per event)
**Triggers → New → Custom Event** — event name must match exactly:
`page_view`, `product_view`, `add_to_cart`, `view_cart`, `begin_checkout`, `purchase`.

### 3c. GA4 (analytics)
1. **Tag → Google Tag** (or GA4 Configuration) with your `G-XXXXXXX` id, trigger **Initialization – All Pages**.
2. One **GA4 Event** tag per event above; *Event Name* = the dataLayer event; tick
   **Send Ecommerce data → Data Layer**. Trigger = the matching Custom Event.

### 3d. Meta Pixel — base (All Pages)
**Tag → Custom HTML**, trigger **Initialization – All Pages**. Replace the id:

```html
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '123456789012345');   // ← your Pixel ID
fbq('track', 'PageView');
</script>
```

### 3e. Meta Pixel — conversion events (with `eventID` for dedup)
One **Custom HTML** tag per event, each with its Custom Event trigger. These read the
pushed `ecommerce` straight from the dataLayer and pass the **same `event_id`** so Meta
dedupes against the server CAPI hit.

**Purchase** (trigger: Custom Event `purchase`):
```html
<script>
(function () {
  var dl = window.dataLayer || [], ec = {};
  for (var i = dl.length - 1; i >= 0; i--) {
    if (dl[i] && dl[i].event === 'purchase' && dl[i].ecommerce) { ec = dl[i].ecommerce; break; }
  }
  var items = (ec.items || []).map(function (it) {
    return { id: String(it.item_id), quantity: it.quantity, item_price: it.price };
  });
  fbq('track', 'Purchase', {
    value: ec.value, currency: ec.currency || 'BDT',
    contents: items, content_ids: items.map(function (i) { return i.id; }),
    content_type: 'product', num_items: ec.total_quantity
  }, { eventID: '{{DLV - event_id}}' });
})();
</script>
```

**ViewContent** (`product_view`), **AddToCart** (`add_to_cart`), **InitiateCheckout**
(`begin_checkout`) — same pattern, change the event name in the loop + the `fbq('track', …)`:

```html
<script>
(function () {
  var NAME = 'add_to_cart', FB = 'AddToCart';               // ← product_view→ViewContent, begin_checkout→InitiateCheckout
  var dl = window.dataLayer || [], ec = {};
  for (var i = dl.length - 1; i >= 0; i--) {
    if (dl[i] && dl[i].event === NAME && dl[i].ecommerce) { ec = dl[i].ecommerce; break; }
  }
  var items = (ec.items || []).map(function (it) {
    return { id: String(it.item_id), quantity: it.quantity, item_price: it.price };
  });
  fbq('track', FB, {
    value: ec.value, currency: ec.currency || 'BDT',
    contents: items, content_ids: items.map(function (i) { return i.id; }), content_type: 'product'
  }, { eventID: '{{DLV - event_id}}' });
})();
</script>
```

> Prefer less code? Install the community **“Facebook Pixel” template** from the GTM gallery
> and set *Event ID* = `{{DLV - event_id}}` — it maps ecommerce automatically.

### 3f. Preview → Submit
**Preview** (Tag Assistant), walk product → add to cart → checkout → success, confirm each
tag fires once with a non-empty `event_id`. Then **Submit / Publish**.

---

## Part 4 — Deploy on the Hostinger VPS

SSH into the VPS, then in the project directory:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:clear && php artisan config:cache
php artisan route:cache && php artisan view:cache
```

The server sender runs via **`dispatchAfterResponse()`** — it fires *after* the customer
gets the page, so **no queue worker is required** and checkout is never slowed.

**Optional (only if you later switch to a real queue):** if you set
`QUEUE_CONNECTION=database`/`redis`, keep a worker alive with a `systemd`/Supervisor unit
running `php artisan queue:work --tries=3` and confirm `php artisan queue:table` +
migrate for the `database` driver. Not needed for the default setup.

Make sure the site is HTTPS (Hostinger → SSL / Let's Encrypt) — Meta requires it.

---

## Part 5 — Test & verify deduplication

1. **Enable test mode**: keep `META_CAPI_TEST_CODE` set, `config:cache`.
2. Place a real test order → land on the order-success page.
3. **Events Manager → Test Events**: you should see **Purchase** arriving from both
   **Browser** and **Server**, and Meta showing them as **Deduplicated** (same `event_id`).
4. Check `storage/logs/laravel.log` — a rejected call logs `Meta CAPI event rejected` with
   the reason (bad token, https, etc.). No log line = accepted.
5. When happy, **remove `META_CAPI_TEST_CODE`** from `.env` and `config:cache` again.

Deterministic id (must match on both sides): `purchase.<invoice>` — see
`App\Support\Tracking::purchaseEventId()`.

---

## Part 6 — (Optional, advanced) Server-side GTM on the VPS via Docker

Only if you want every event server-routed with first-party cookies. Otherwise skip.

1. Install Docker on the VPS: `curl -fsSL https://get.docker.com | sh`.
2. In GTM, create a **Server** container; copy its **Container Config** string.
3. Run the tagging server:
   ```bash
   docker run -d --name sgtm -p 8080:8080 --restart unless-stopped \
     -e CONTAINER_CONFIG='PASTE_CONTAINER_CONFIG' \
     gcr.io/cloud-tagging-10302018/gtm-cloud-image:stable
   ```
4. Point a subdomain at it: `sgtm.yourdomain.com` → reverse-proxy (Nginx/Caddy) to
   `127.0.0.1:8080` with a Let's Encrypt certificate. Put the URL in `.env` as `SGTM_ENDPOINT`.
5. In the **web** container, set the GA4 tag’s *server container url* to `https://sgtm.yourdomain.com`.
6. In the **server** container add the **Meta Conversions API** tag (event name mapped, *Event ID* =
   `{{event_id}}`, Pixel ID + token). Now the browser sends once; sGTM forwards to GA4 + Meta CAPI.
7. If you use sGTM for Purchase, **turn off** the Laravel sender (`META_CAPI_ENABLED=false`) to
   avoid a third hit (dedup still protects you, but keep it clean).

---

## QA checklist

- [ ] `.env` has `META_CAPI_ENABLED=true`, `META_PIXEL_ID`, `META_CAPI_TOKEN`; `config:cache` run.
- [ ] GTM published: GA4 + Meta base + per-event Meta tags, each with `eventID = {{DLV - event_id}}`.
- [ ] Old GTM snippet removed from Admin → Header/Body code (see `README.md` §2).
- [ ] Test order → Test Events shows Purchase **Browser + Server = Deduplicated**.
- [ ] `META_CAPI_TEST_CODE` removed after verification.
- [ ] Site is HTTPS.
