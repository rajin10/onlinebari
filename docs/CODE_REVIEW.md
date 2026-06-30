## Executive Summary

The "eureka" multi-vendor e-commerce platform exhibits serious, systemic security weaknesses across authentication, authorization, file handling, secret management, and output encoding. The most urgent issues are live production secrets committed to git (MySQL password and Laravel `APP_KEY` frozen into `bootstrap/cache/config.php` at HEAD), an unauthenticated arbitrary-file-upload-to-webroot via the public contact form (RCE / stored XSS), and a vendor wallet-balance inflation flaw (negative-amount withdrawal) that mints spendable platform funds. Authorization is broken pervasively: a recurring "blind `Model::find($request->id)`" anti-pattern produces dozens of in-role IDORs letting vendors cancel/deliver/print other vendors' orders, corrupt cross-vendor balances and inventory, and letting any account user delete/overwrite other users' blogs and classified ads. File-upload handlers across contact, blog, avatar, product, and KYC flows skip type validation and trust the client-supplied extension, writing attacker-named files into web-served directories. Output encoding is inconsistent — multiple `{!! !!}` sinks render vendor/account-user content unescaped, producing stored and reflected XSS, including a vendor→admin privilege-crossing path. Authentication hardening is weak throughout: no login throttling, an SMS recovery flow that destroys passwords before proving phone possession, and unauthenticated maintenance/notification endpoints. The top risks demand immediate credential rotation + history scrubbing, server-side price/amount validation, a uniform upload allowlist, and ownership scoping on every mutating controller action.

## Severity Summary

| Severity | Confirmed Findings |
|----------|-------------------|
| Critical | 6 |
| High | 27 |
| Medium | 6 |
| Low | 2 |
| **Total** | **41** |

## Confirmed Findings

### Critical

### [CRITICAL] Vendor can inflate wallet balance via negative withdrawal amount
**File:** `app/Http/Controllers/WithdrawController.php:34` (also referenced at :20)
**Class:** price-tampering
**Impact:** Direct theft of platform funds. An authenticated vendor can mint arbitrary wallet balance and then withdraw it for real after admin approval.
**Exploit scenario:** An approved vendor with at least one payout method configured POSTs `vendor/withdraw/create` with `amount=-1000000&method=1`. The only guard `if($vendor->amount < $request->amount)` evaluates `0.00 < -1000000` (false), so execution reaches `$vendor->amount -= $request->amount` = `0 - (-1000000) = 1000000`, persisting the inflated balance and writing a pending `Withdraw` row. The vendor then submits a normal positive withdrawal against the minted balance. No `validate()`, FormRequest, or `min:` rule exists; CSRF does not help because the attacker is the authenticated vendor.
**Fix:** Validate `['amount' => 'required|numeric|min:1', 'method' => 'required|in:1,2,3,4']`; re-check `$request->amount > 0 && $request->amount <= $vendor->amount`; wrap the balance decrement and `Withdraw::create` in a `DB::transaction` with `lockForUpdate()`. (Duplicate finding at the same file/method, line 20, is merged here.)

### [CRITICAL] Unauthenticated arbitrary file upload to webroot via public contact form
**File:** `app/Http/Controllers/Frontend/ContactController.php:52` (validate block :45-51, move :61)
**Class:** file-upload
**Impact:** Unauthenticated remote code execution (upload `.php` to webroot) and/or unconditional unauthenticated stored XSS (`.html`/`.svg`).
**Exploit scenario:** Anonymous attacker GETs `/contact/form` to read the CSRF token, then POSTs `/contact/create` (routes/web.php:240, outside any auth group) with the required text fields plus a multipart `cover_photo` named `evil.php`. The `validate()` block has no rule for `cover_photo`; the filename is built from client-controlled `getClientOriginalExtension()` and moved into `public/uploads/contact/`. The root `public/.htaccess` `RewriteCond !-f` serves the existing `.php` directly, executing it on a mod_php host. No `.htaccess` disables PHP under `public/uploads/`.
**Fix:** Add `'cover_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'`, reject SVG, derive the stored extension from the validated MIME via `->extension()`, store outside webroot or serve via a controller, and drop a no-exec rule in the uploads directory.

### [CRITICAL] Live production database password committed to git history (commit 7b0b730)
**File:** `bootstrap/cache/config.php:359` (also 379/399/414) — in commit `7b0b730`, deleted from working tree in `9597a08`
**Class:** secrets
**Impact:** Full read/write/delete access to the production MySQL database (orders, customers, payments, password hashes) for anyone who can clone the repo or read its history.
**Exploit scenario:** Anyone with repo/history access runs `git show 7b0b730:bootstrap/cache/config.php | grep password` and obtains database/username `u562520413_eureka2` and password `Rajin@2025` (resolved literals, not env placeholders). NOTE (verified post-review): the file is NOT in the current HEAD tree — it was committed in `7b0b730` and later deleted in `9597a08` (current HEAD). That deletion does **not** remediate: commit `7b0b730` is an ancestor of HEAD, so the secret remains fully readable in history. With network reach to the DB host, the attacker connects directly.
**Fix:** ROTATE the MySQL password immediately (deletion does not remediate — the secret persists in commit `7b0b730`). Purge with git filter-repo/BFG and force-push. Restore `bootstrap/cache/.gitignore` (`*` and `!.gitignore`).

### [CRITICAL] Application encryption key (APP_KEY) leaked in committed config cache
**File:** `bootstrap/cache/config.php:128`
**Class:** secrets
**Impact:** Forgery of signed/temporary URLs, decryption and forgery of encrypted cookies/session data, and tampering with encrypted values — undermining authentication and integrity across the app. (Severity escalated by reviewers to critical because the GitHub repo is public.)
**Exploit scenario:** Anyone with repo/history access reads `'key' => 'base64:e09d+/Ie8cw2sHPnCYrzZMFsdmWzN5j7nn2eg+x6y3A='` at `7b0b730:bootstrap/cache/config.php:128` (byte-for-byte identical to the working-tree `.env` `APP_KEY`). With Laravel's `APP_KEY` they forge `URL::signedRoute` links (password-reset/verification), decrypt/forge encrypted cookies and `Crypt::encrypt` payloads, and tamper with session integrity. (Verified post-review: in reachable history via `7b0b730`, not in the current HEAD tree — rotation still required.)
**Fix:** Rotate with `php artisan key:generate` (plan re-encryption of any `APP_KEY`-encrypted columns or use `previous_keys` for graceful rotation), then purge from history alongside the DB-password remediation.

### [CRITICAL] bootstrap/cache/ not gitignored — resolved config (with secrets) committable (root cause)
**File:** `.gitignore` (missing entry; `bootstrap/cache/{events,packages,services,routes-v7}.php` are currently tracked, and `config.php` was tracked through `7b0b730`)
**Class:** secrets
**Impact:** Every `php artisan config:cache` run risks re-committing the full set of production secrets (DB password, APP_KEY, mail/payment keys), defeating the protection that gitignoring `.env` provides.
**Exploit scenario:** The root `.gitignore` excludes `.env` and `*.log` but has no `bootstrap/cache/` rule, and the Laravel-shipped `bootstrap/cache/.gitignore` (`*`, `!.gitignore`) is absent. `config:cache` inlined every `.env` secret into `bootstrap/cache/config.php`, which was committed in `7b0b730` (deleted again in `9597a08`, but the secret persists in reachable history); `git check-ignore` returns nothing for these files, so a future cache run can re-leak on any commit. (`.env` itself was never committed — `git log -- .env` is empty.)
**Fix:** Restore `bootstrap/cache/.gitignore` (`*` + `!.gitignore`) or add `/bootstrap/cache/*.php` (plus `error_log`) to root `.gitignore`; then `git rm --cached bootstrap/cache/*.php`. This is the structural fix preventing recurrence after rotation.

### [CRITICAL] Unauthenticated mass push-notification blast endpoint
**File:** `app/Http/Controllers/HomeController.php:181` (route routes/web.php:264)
**Class:** auth-bypass
**Impact:** An attacker can broadcast arbitrary push notifications (phishing links, spam) to every registered device token, abusing Firebase quota and damaging user trust. (Reviewers noted CSRF partially raises attacker effort, settling near high; retained as critical-tier auth-bypass given no authentication.)
**Exploit scenario:** `/send-notification` (routes/web.php:264) has no middleware. `sendNotification()` pulls `DeviceId::pluck('device_id')->all()` and fires a Firebase push using attacker-supplied `title`/`body`; `curl_exec` runs (line 211) before the leftover `dd($response)` (line 213), so delivery completes. A scripted attacker fetches a CSRF token from any page (no login required) and POSTs the blast.
**Fix:** Restrict the route to `['auth','admin']` (role_id==1), validate `title`/`body`, remove the leftover `dd()`, and never expose a fan-out notification endpoint publicly.

### High

### [HIGH] Customer blog thumbnail upload — no validation, client-controlled extension (RCE / stored XSS)
**File:** `app/Http/Controllers/blogControler.php:137` (route routes/web.php:203)
**Class:** file-upload
**Impact:** An authenticated low-privilege account user can write executable/HTML/SVG content to the webroot: RCE or stored XSS affecting all visitors.
**Exploit scenario:** A vendor/customer (AccountMiddleware allows role_id 2 or 3) POSTs `/create-blog` → `store2()`, which builds `$thumbnailName = $rand.$thumbnail->getClientOriginalName()` (client extension preserved, only a 999/1000 numeric prefix) and moves it into `uploads/blogs/<date>/` under `public/`. Uploading `shell.php` and requesting it executes under the cPanel `AddHandler` for `.php`. Same unsafe pattern in admin `store()` (:39) and `update_exit_blog()` (:101).
**Fix:** Validate `'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'` (no SVG); generate the stored name yourself with a random base and an extension derived from the validated MIME via `->extension()`.

### [HIGH] Vendor downloadable product files saved to webroot — no validation, client extension (RCE)
**File:** `app/Http/Controllers/Vendor/ProductController.php:255` (move :260; also update :528/:761, Admin ProductController :604/:961)
**Class:** file-upload
**Impact:** A vendor can achieve RCE by uploading a PHP file into the public download directory.
**Exploit scenario:** In `store()` the downloadable `files` array is absent from the validation rules (:62-96 validate only image/images). Each file is named via `getClientOriginalExtension()` and moved into `public/uploads/product/download/` (web-served, no exec restriction). A vendor (self-service signup, role_id==2) uploads `evil.php` as a downloadable file and fetches `/uploads/product/download/<name>.php` directly, bypassing the front controller via the `!-f` rewrite.
**Fix:** Validate `files.*` with a strict allowlist (`mimes:pdf,zip,epub,mp3` + size cap), derive the extension from the validated MIME, and store downloadable files OUTSIDE `public/`, streaming them through an authorized controller route.

### [HIGH] Hardcoded BDCourier API key as fallback secret in source
**File:** `app/Http/Controllers/Admin/Ecommerce/OrderController.php:749` (also rendered at resources/views/admin/e-commerce/setting/courierIndex.blade.php:116)
**Class:** secrets
**Impact:** Leak of a paid third-party API credential (billing/quota abuse, arbitrary phone fraud-history lookups); the key cannot be rotated without a code change and persists in history (present since the `Init` commit).
**Exploit scenario:** Anyone with repo access reads `setting('BDCOURIER_API_KEY') ?? 'ZkEEfBAEBRxVkgcLpR3Z5e3sPHQ6dy0XViGTqYyg4clRjj06rRKmAs41Smp2'` and calls `https://bdcourier.com/api/courier-check` with `Authorization: Bearer <key>`.
**Fix:** Remove the literal, rotate the credential, source it exclusively from the encrypted setting/env, and fail closed (skip the external call) when missing.

### [HIGH] Hardcoded Firebase FCM server key committed in source
**File:** `app/Http/Controllers/Admin/Ecommerce/OrderController.php:590` (duplicated at `app/Http/Controllers/HomeController.php:185` and `app/Http/Controllers/Vendor/ProductController.php:829`)
**Class:** secrets
**Impact:** Anyone with repo access can send arbitrary push notifications to the app's entire audience (phishing/spam); the key cannot be rotated without a code deploy.
**Exploit scenario:** The literal `$SERVER_API_KEY = 'AAAA9Ek9F7U:APA91b...'` is used as the FCM `Authorization: key=` bearer against `fcm.googleapis.com/fcm/send`. An attacker extracts it from source and pushes directly. (Note: this legacy FCM key/endpoint may be deprecated by Google, reducing real-world deliverability, but the committed-secret defect is genuine.) Merges the two duplicate FCM-key findings (OrderController:590 and HomeController:185).
**Fix:** Move the key to `config('services.fcm.key')`, purge from git history, rotate it in the Firebase console, and migrate to FCM HTTP v1 with a service account.

### [HIGH] In-role IDOR: vendor can cancel ANY order and corrupt other vendors' balances
**File:** `app/Http/Controllers/Vendor/OrderController.php:138` (findOrFail :140; route routes/vendor.php:62)
**Class:** idor
**Impact:** Cross-vendor sabotage: cancel competitors' valid orders, drain their and the admin's `VendorAccount` balances, and re-inflate stock.
**Exploit scenario:** A vendor GETs `vendor/order/status/cancel/{id}` for an arbitrary order id. `statusCancel()` loads `Order::findOrFail($id)` with no ownership scope; for status 0/1 it iterates `orderDetails`, decrements each product-owning vendor's and the admin's `VendorAccount` balance, and restocks. `statusProcessing`/`statusShipping` correctly scope by `multi_order.vendor_id = auth()->id()`, proving the omission is a defect.
**Fix:** Verify the order contains at least one product owned by `auth()->id()`, and only adjust the cancelling vendor's own `multi_order` rows / own `VendorAccount` — mirror the existing `vendor_id` scoping.

### [HIGH] In-role IDOR: vendor can read any order's customer PII via show/print/invoice
**File:** `app/Http/Controllers/Vendor/OrderController.php:86` (print :99/:101, invoice :217/:219; routes routes/vendor.php:48,56,57)
**Class:** idor
**Impact:** Marketplace-wide customer PII disclosure (name, address, town/district, post code, phone, email) by id enumeration.
**Exploit scenario:** A vendor requests `vendor/order/print/{id}` or `vendor/order/invoice/{id}` for arbitrary ids. Both call `Order::findOrFail($id)` with no scoping and render `vendor/order/invoice.blade.php`, which prints customer email/phone/address unconditionally before any vendor-scoped query. (The `show()` view incidentally 500s on a non-owned order, but print/invoice fully leak.) Merges the separate print()/invoice() IDOR finding at :99.
**Fix:** Before rendering, confirm an `OrderDetails` row exists where `product_id IN (Product::where('user_id',auth()->id())->pluck('id'))` for this order; otherwise `abort(403)`.

### [HIGH] Vendor can mark ANY order delivered (unscoped findOrFail)
**File:** `app/Http/Controllers/Vendor/OrderController.php:197` (route routes/vendor.php:63)
**Class:** idor
**Impact:** A vendor can force-complete (status=3) any pending/processing order platform-wide, corrupting fulfilment state.
**Exploit scenario:** GET `vendor/order/status/delivered/{id}` for any order id → `statusDelivered()` loads `Order::findOrFail($id)` with no vendor filter and flips status to 3, unlike the `multi_order`-scoped `statusProcessing`/`statusShipping`. (No fund movement occurs in this method.)
**Fix:** Restrict the status change to orders containing the vendor's products / the per-vendor `multi_order` row scoped by `vendor_id=auth()->id()`.

### [HIGH] In-role IDOR: vendor can read/replace another vendor's product gallery images
**File:** `app/Http/Controllers/Vendor/ProductController.php:607` / `:614` (routes routes/vendor.php:15-16)
**Class:** idor
**Impact:** Cross-vendor data destruction and content injection: delete a competitor's gallery images (DB rows + unlinked files) and inject arbitrary images onto their listing; read another vendor's image set.
**Exploit scenario:** `getProductImage($id)` returns `ProductImage::where('product_id',$id)->get()` unscoped. `updateImage()` operates entirely on `$request->id`: `ProductImage::whereNotIn('id',$ids)->where('product_id',$request->id)` selects/unlinks/deletes the victim's images (send `old=[0]` to wipe all) then inserts via `ProductImage::create(['product_id'=>$request->id,...])`. Every other CRUD method in the controller scopes by `user_id`, confirming the omission. Merges the two overlapping gallery-image IDOR findings (:614 and :607).
**Fix:** Resolve the product scoped to the owner first: `Product::where('id',$request->id)->where('user_id',auth()->id())->firstOrFail()`, and operate only on its images.

### [HIGH] In-role IDOR: vendor can delete any product's downloadable file
**File:** `app/Http/Controllers/Vendor/ProductController.php:717` (route routes/vendor.php:24)
**Class:** idor
**Impact:** Destruction of another vendor's digital goods (the deliverable customers paid for), causing fulfilment failures and revenue loss.
**Exploit scenario:** GET `vendor/delete/product/download/{id}` → `deleteDownloadFile($id)` runs `DownloadProduct::findOrFail($id)`, unlinks the file, and deletes the row with no ownership check. Ids are auto-increment and enumerable. The sibling `updateDownloadFile` (:747) DOES scope by `user_id`, proving the gap.
**Fix:** Load the `DownloadProduct`, then verify `$download->product->user_id === auth()->id()` (or join Product on user_id) before deleting; otherwise `abort(403)`.

### [HIGH] In-role IDOR: vendor can sell/order against another vendor's product (mismatched commission)
**File:** `app/Http/Controllers/Vendor/CustomOrderController.php:99` / `:129` (route routes/vendor.php:29)
**Class:** idor
**Impact:** Cross-tenant inventory/balance corruption and commission misattribution: a vendor places a custom order against any product id, decrementing the victim vendor's stock and mutating their and the admin's `VendorAccount` balances. (Note: earnings credit the product's true owner while the `Commission` row is attributed to `auth()->id()`.)
**Exploit scenario:** POST `vendor/product/order` with `id=<other vendor's product>`. `orderProductStore()` resolves `Product::find($request->id)` unscoped (unlike `orderProduct($id)` which uses `firstOrFail` with `user_id`). Combined with unvalidated `qty`, a negative qty drives totals negative and inflates the victim's stock.
**Fix:** Scope to the owner: `Product::where('id',$request->id)->where('user_id',auth()->id())->firstOrFail()`; validate `'id'=>'required|integer','qty'=>'required|integer|min:1'`; attribute commission/earnings consistently within a transaction. (This shares the qty defect with the POS finding below.)

### [HIGH] Vendor POS order accepts unvalidated quantity (negative qty inflates stock, drives totals/commission negative)
**File:** `app/Http/Controllers/Vendor/CustomOrderController.php:130` (also Admin `CustomOrderController.php:146`)
**Class:** price-tampering
**Impact:** A vendor (or admin) can corrupt inventory (free stock), write negative-total orders, and skew commission/wallet accounting; signed DB columns persist the negatives.
**Exploit scenario:** `orderProductStore` computes `$subtotal = $product->discount_price * $request->qty` with no rule constraining `qty`. A negative qty yields a negative subtotal/total, and `$product->quantity = $product->quantity - $request->qty` (line 207) with a negative qty INCREASES stock; the commission block (:184-200) derives from the negative grand_total and decrements the admin/vendor `VendorAccount`.
**Fix:** Add `'qty' => 'required|integer|min:1'` and a stock check (`max:'.$product->quantity`) in both Vendor and Admin `orderProductStore` before any total/stock computation.

### [HIGH] Negative withdrawal amount inflates vendor wallet (duplicate confirmation)
**File:** `app/Http/Controllers/WithdrawController.php:34`
**Class:** price-tampering
**Impact:** Same vector as the critical balance-inflation finding — listed here as the independently confirmed `spec-payment` report; remediate together. An authenticated vendor inflates payable balance and withdraws unearned money after admin approval (which re-validates nothing).
**Exploit scenario:** As above — `amount=-100000` passes `$vendor->amount < $request->amount` and `$vendor->amount -= $request->amount` adds to the balance; the inflated value is later cashed out via a normal positive withdrawal.
**Fix:** See the critical WithdrawController finding — validate `min:1`, re-check bounds, transaction + row lock.

### [HIGH] IDOR + moderation bypass: any account user can toggle/edit/delete any blog (including admin blogs)
**File:** `app/Http/Controllers/blogControler.php:62` (status), `:74`/`:76` (destory), `:96` (update_exit_blog); routes routes/web.php:204-207
**Class:** idor
**Impact:** Cross-account content tampering, destruction of arbitrary (incl. admin/CEO) blogs, and moderation bypass — a customer self-publishes their unreviewed blog and rewrites/unpublishes others.
**Exploit scenario:** `status($id)`/`destory($id)`/`update_exit_blog()` use `Blog::find($id|$request->power)` with no `user_id` scope, in the `['account','auth']` group. `store2` creates blogs `status=false`; `getAllBlogs` only surfaces `status=true`; a user GETs `blog/status/{ownId}` to self-approve, or targets any id to delete/overwrite. Merges the standalone destory (:74) and update_exit_blog (:96) IDOR findings.
**Fix:** Scope all user-facing blog mutations to `where('user_id',auth()->id())->firstOrFail()`; move admin-blog status/destroy/update behind admin-only middleware; do not let a self-service status toggle bypass moderation.

### [HIGH] IDOR: classified-ad delete/edit/update operate on any user's ad
**File:** `app/Http/Controllers/Frontend/adsController.php:20` (delete), `:25` (edit), `:74`/`:75` (update); routes routes/web.php:209-215
**Class:** idor
**Impact:** Any logged-in user can delete, read, or fully rewrite (title/price/contact/description/image) another user's classified ad — and `update()` reassigns `user_id` to the attacker, hijacking the listing.
**Exploit scenario:** `delete($id)` → `Unproduct::find($id)->delete()`; `edit($id)` → renders any record; `update()` → `Unproduct::find($request->power)` then `$product->update(['user_id'=>auth()->id(),...])`. None scope by owner (the `list()` method does). Attacker submits `power=<victim_ad_id>`. Merges the standalone update() takeover (:74) and the edit-read (:25, downgraded to low) findings.
**Fix:** Scope every lookup to the owner: `Unproduct::where('id',$id)->where('user_id',auth()->id())->firstOrFail()`; never set `user_id` from request context on update.

### [HIGH] No rate limiting on login / custom login bypasses ThrottlesLogins
**File:** `app/Http/Controllers/Auth/LoginController.php:153` (login), `superLogin` (route routes/web.php:76); GET `user/login` (routes/web.php:77)
**Class:** rate-limit
**Impact:** Unlimited online brute-force / credential stuffing against customer, vendor, and ADMIN accounts with no lockout or delay.
**Exploit scenario:** The overridden `login()` loops `Auth::guard('web')->attempt()` over `['phone','username','email']` (3x per request) with no `ThrottlesLogins`; `superLogin()` is fully custom with no throttle. No throttle middleware exists on the auth routes, no `RateLimiter::for('login')`, no CAPTCHA. The GET `/user/login` route is CSRF-exempt, enabling token-free guessing. Merges the LoginController (:153) and routes-wiring (:76) brute-force findings.
**Fix:** Apply `throttle:5,1` (or a named limiter keyed on username+IP) to `admin/login`, user login, register, and password-reset routes, and/or restore the framework `ThrottlesLogins` behavior.

### [HIGH] SMS password recovery resets password before verifying phone possession
**File:** `app/Http/Controllers/Frontend/AccountController.php:158` (save :171-172; route routes/web.php:81)
**Class:** auth-bypass (forced reset / missing possession proof)
**Impact:** Unauthenticated, repeatable forced-reset / account lockout of any account (including admin/vendor) whose phone is known; phone numbers are enumerable.
**Exploit scenario:** `pasm()` does `User::where('phone',$request->phone)->first()` then immediately `$user->password=bcrypt($rand); $user->save()` BEFORE the SMS is sent, with no rate limiting or CAPTCHA. The new password goes only to the legitimate phone, but the victim's existing password is already destroyed. Enumeration via `sendotp()` "This number already have an account" (`RegisterController.php:226`).
**Fix:** Send a one-time code, change the password only after the user submits the correct code, add throttling, and avoid distinguishing "number exists" responses.

### [HIGH] Unauthenticated GET route runs Artisan cache/config/route/storage commands
**File:** `routes/web.php:270`
**Class:** auth-bypass
**Impact:** Any anonymous user can repeatedly clear production caches (DoS via forced recompilation) and run `storage:link` filesystem operations.
**Exploit scenario:** `/cache` is registered top-level with no middleware. A looped GET runs `cache:clear`, `config:clear`, `view:clear`, `route:clear`, `storage:link` on the live server; CSRF does not apply to GET.
**Fix:** Remove from production, or gate behind `['auth','admin']` + throttle and `app()->environment('local')`.

### [HIGH] Customer avatar upload — no file validation, client extension to webroot
**File:** `app/Http/Controllers/Frontend/AccountController.php:111` (move :123; route routes/web.php:165)
**Class:** file-upload
**Impact:** Authenticated customer/vendor can plant a `.php` (RCE) or `.html`/`.svg` (stored XSS) file in `public/uploads/member/`.
**Exploit scenario:** `accountUpdate()` validates name/username/email/phone but has no rule for `avatar`. The filename uses `getClientOriginalExtension()` and is moved into `public_path('uploads/member')`. Robust path: upload `evil.html`/`evil.svg`, read the URL off the rendered profile img, and send the same-origin link to a victim (stored XSS); `.php` yields RCE where PHP executes in the webroot.
**Fix:** Add `'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'`, derive the extension from the validated MIME (excluding SVG).

### [HIGH] Classified-ad primary image accepted without type validation (only 'required')
**File:** `app/Http/Controllers/Frontend/adsController.php:45` (rule :37, move :51; route routes/web.php:214; update :88)
**Class:** file-upload
**Impact:** Authenticated customer can write a `.php` (RCE) or `.html`/`.svg` (stored XSS) file to `public/uploads/product/` via the classified-ad primary image field.
**Exploit scenario:** `store()` validates the primary `image` only as the literal string `'required'` (not the Laravel `image` type rule), while gallery `images.*` uses `image`. The file is named via `getClientOriginalExtension()` and moved BEFORE any `Image::make()` re-encode; the raw file persists on disk even though processing then throws. `.html`/`.svg` stored XSS is universal; `.php` RCE on mod_php hosts.
**Fix:** Change to `'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'`, derive the extension from the validated MIME, and validate before moving.

### [HIGH] Stored XSS via vendor-controlled product descriptions (public + admin)
**File:** `resources/views/frontend/single-product.blade.php:511` / `:518` (also `resources/views/admin/e-commerce/product/show.blade.php:360`, `resources/views/components/product-list-view.blade.php:18`); source `app/Http/Controllers/Vendor/ProductController.php:141-142`
**Class:** xss
**Impact:** A vendor injects `<script>` into a product description that executes for every public visitor and for the admin who opens the product — a vendor→admin privilege escalation (admin session/CSRF-token theft).
**Exploit scenario:** `short_description`/`full_description` are stored verbatim with only `string` validation, then rendered unescaped with `{!! !!}`. A vendor can self-activate `status=true` (`status()` :666-679), and the public `productDetails` query gates only on `status` (not `is_aproved`), so the payload is served to all visitors; the admin product-show view renders the same unsanitized full_description.
**Fix:** Sanitize rich-text descriptions on input with an HTML purifier (safe allowlist) or render via a sanitizing component; apply consistently across the public single-product view, the search list component, and the admin show view.

### [HIGH] Stored XSS via account-user blog descriptions rendered unescaped to all visitors
**File:** `resources/views/frontend/blog/show.blade.php:135` (also `resources/views/frontend/blog/all.blade.php:109`); source `app/Http/Controllers/blogControler.php:152`
**Class:** xss
**Impact:** A low-privilege account user stores JavaScript in a blog description that executes for every visitor (cookie/session theft, drive-by actions).
**Exploit scenario:** `store2()` saves `$request->descripiton` with no sanitization. `getBlogByID` (`Blog::find($id)`, no status filter) renders `frontend/blog/show.blade.php` with `{!! $blog->description !!}`, so the payload is reachable via a shared link even before admin approval; once approved it also appears on the public listing.
**Fix:** Sanitize on input with an HTML purifier (safe allowlist) or render via `{{ }}`/a sanitizing component; restrict `getBlogByID` to `status=true`.

### [HIGH] Stored XSS via account-user classified ad descriptions on public page
**File:** `resources/views/frontend/single-unproduct.blade.php:166`; source `app/Http/Controllers/Frontend/adsController.php:66` (update :113); route routes/web.php:222 (public show)
**Class:** xss
**Impact:** A low-privilege account user injects `<script>` into an ad description that executes for any unauthenticated visitor of the public ad page.
**Exploit scenario:** `store()` stores `$request->description` verbatim (only `string` validation). The public `GET /classic/product/{slug}` → `show()` loads the ad by slug with no status gate and renders `{!! $product->description !!}`. The attacker bypasses the client Summernote editor with a raw POST payload.
**Fix:** Sanitize the ad description on input with an HTML purifier or render with `{{ }}`/a sanitizing component.

### [HIGH] Reflected XSS in product search via keyword query parameter
**File:** `resources/views/frontend/search-product.blade.php:239`; source `app/Http/Controllers/Frontend/ProductController.php:420-422`; route routes/web.php:99 (public)
**Class:** xss
**Impact:** Unauthenticated reflected XSS — script runs in the victim's session origin, enabling session/cookie theft or actions as the victim.
**Exploit scenario:** The controller sets `$key = $request->keyword` (raw) and the view emits `var key = '{!! $key !!}';` inside a `<script>` block. `/product/search?keyword='-alert(document.cookie)-'` breaks out of the single-quoted JS string. The route is public, no auth/CSP.
**Fix:** Use `var key = @json($key);` (or `Js::from($key)`) for the JS variable and `{{ }}` for HTML context; never interpolate raw request input into a script string.

### [HIGH] Vendor onboarding documents (KYC) allow SVG via image rule + client extension (stored XSS)
**File:** `app/Http/Controllers/Frontend/VendorController.php:126` (rules :110-115; also `Vendor/ProfileController.php:116+`, `Admin/Ecommerce/VendorController.php`)
**Class:** file-upload
**Impact:** A malicious SVG uploaded as a KYC document executes script in the app origin when opened by direct URL (e.g. an admin reviewing KYC), enabling session/account compromise. (In-app previews use `<img>` which does not execute SVG script, so a top-level navigation or shared link is required — reviewers split low/medium.)
**Exploit scenario:** `setupVendor()` validates documents with the bare `image` rule, whose Laravel allowlist includes SVG. Files are saved with `getClientOriginalExtension()` into `public/uploads/shop/...`. An attacker uploads an SVG with embedded `<script>` and lures a reviewer to open the raw `/uploads/shop/nid/<file>.svg`.
**Fix:** Use `mimes:jpg,jpeg,png,webp` instead of bare `image`, derive the extension from the validated MIME, and if SVG must be supported, sanitize it or serve with `Content-Disposition: attachment` + a restrictive CSP.

### Medium

### [MEDIUM] Financial withdraw approval/cancel/delete exposed as GET (CSRF-prone)
**File:** `routes/admin.php:111-113`
**Class:** csrf
**Impact:** Money-moving approval/cancellation/deletion of vendor payouts triggerable via forged GET in an authenticated admin's browser. (SameSite=Lax defeats the `<img>` vector but not lured top-level navigation, so downgraded to medium.)
**Exploit scenario:** `vendor.withdraw.aprove/cancel/delete` are GET routes (no CSRF token on GET). An admin lured to click an attacker link / redirect to `.../admin/vendor/withdrawd/{id}` triggers the unprotected state change; `cancel()` even credits `user->wallate`.
**Fix:** Convert to POST/PUT/DELETE submitted via forms with `@csrf`, not GET links.

### [MEDIUM] IDOR: any account user can delete another user's wishlist item
**File:** `app/Http/Controllers/Frontend/wishlistController.php:35` (delete :36; route routes/web.php:186)
**Class:** idor
**Impact:** Cross-account data tampering — any logged-in user deletes arbitrary users' wishlist rows by iterating ids. (Low-sensitivity, recoverable asset → medium.)
**Exploit scenario:** GET `/wishlist/remove/{id}` → `wishlist::find($id)->delete()` with no `user_id` scope (the `index()` method scopes correctly).
**Fix:** Scope by owner: `wishlist::where('id',$id)->where('user_id',auth()->id())->firstOrFail()->delete()`.

### [MEDIUM] In-role IDOR: vendor can sell another vendor's product as their own custom order
**File:** `app/Http/Controllers/Vendor/CustomOrderController.php:99`
**Class:** idor
**Note:** See the High "vendor can order against another vendor's product" entry above — this is the same defect reported via a different source. Impact is cross-tenant inventory/balance corruption and commission misattribution (no direct fund exfiltration to the attacker), hence medium under strict calibration.
**Fix:** As above — scope the product to `auth()->id()` and validate `id`/`qty`.

### [MEDIUM] CSV/formula injection in product export (vendor fields reach admin's spreadsheet)
**File:** `resources/views/admin/e-commerce/product/exports.blade.php:36` (also :37-38); exporter `app/Exports/ProductsExport.php`
**Class:** csv-injection
**Impact:** A vendor plants spreadsheet formula payloads (e.g. `=HYPERLINK(...)`/`=WEBSERVICE(...)`) in product title/description; when an admin exports and opens the `.xlsx`, formula-driven data exfiltration (or DDE, subject to prompts) executes — a vendor→admin crossing.
**Exploit scenario:** `ProductsExport` (FromView) renders vendor-controlled fields into cells. PhpSpreadsheet's HTML reader types a leading-`=` string as `TYPE_FORMULA` (the quote-prefix defense requires a data-type attribute the view never sets), so the cell becomes a live formula. (Leading `+`/`-`/`@` are inert on this path.)
**Fix:** Prefix a single quote (or strip/escape a leading `=`,`+`,`-`,`@`,tab,CR) on every user-controlled export string, or use a FromArray export forcing string cell types.

### [MEDIUM] TLS certificate verification disabled on BDCourier API call leaks Bearer key
**File:** `app/Http/Controllers/Admin/Ecommerce/OrderController.php:755` (header :757; FCM call :612)
**Class:** tls-verification
**Impact:** A network MITM between the server and `bdcourier.com`/`fcm.googleapis.com` can intercept the Bearer key or return forged fraud-check results influencing the admin's fraud decision.
**Exploit scenario:** `fraud_checker()` sets `CURLOPT_SSL_VERIFYPEER => false` while sending `Authorization: Bearer <apiKey>`. An attacker presenting any certificate reads the key or forges the success-ratio response. (Requires a MITM position → medium.) Merges the two TLS findings (:755 and the combined :755/:612 report).
**Fix:** Remove `CURLOPT_SSL_VERIFYPEER => false` (keep `CURLOPT_SSL_VERIFYHOST` at 2); configure `CURLOPT_CAINFO` if a CA bundle is needed, or use Laravel's `Http` client (verifies by default).

### [MEDIUM] updateImage() deletes all gallery images then crashes on undefined $product (data loss)
**File:** `app/Http/Controllers/Admin/Ecommerce/ProductController.php:1077` (delete loop :1056-1064; route routes/admin.php:139)
**Class:** logic-bug
**Impact:** Using the admin "update product image" feature irreversibly deletes all of a product's gallery images and files, then errors before saving the replacements — guaranteed data loss on any invocation that includes an upload.
**Exploit scenario:** `updateImage()` unlinks/deletes every `ProductImage` for `$request->id`, then calls `$product->images()->create([...])` where `$product` is never defined (no parameter, no property), throwing a fatal error on the first iteration. Validation also checks `photos`/`photos.*` while the code reads `images`, so uploads are never validated. (Admin-only; correctness/data-loss, not cross-tenant.)
**Fix:** Load `$product = Product::findOrFail($request->id)`, validate the correct field (`images`/`images.*` with image mimes), and only delete old images after new uploads succeed inside a DB transaction.

### Low

### [LOW] IDOR (read): any account user can view another user's classified ad in the edit form
**File:** `app/Http/Controllers/Frontend/adsController.php:25` (route routes/web.php:210)
**Class:** idor
**Impact:** Minor information disclosure (loads another user's ad, including contact details, into the edit form). Most fields are already public via the `show($slug)` page; it is primarily a precursor to the `update()` takeover (covered High above).
**Exploit scenario:** GET `/ads/edit/{id}` → `Unproduct::find($id)` unscoped, rendered into the edit view.
**Fix:** `Unproduct::where('id',$id)->where('user_id',auth()->id())->firstOrFail()`.

### [LOW] IDOR (read): any account user can view another user's blog in the edit form
**File:** `app/Http/Controllers/blogControler.php:163` (route routes/web.php:206)
**Class:** idor
**Impact:** Minor information disclosure (loads another user's blog, including unpublished drafts, into the edit form). Published content is already public via `getBlogByID`; primarily a setup step for the `update_exit_blog()` takeover (covered High above).
**Exploit scenario:** GET `/blog-edit/{id}` → `blog_edit_form2()` lists own blogs but loads the editable record via `Blog::find($id)` unscoped.
**Fix:** `Blog::where('id',$id)->where('user_id',auth()->id())->firstOrFail()`.

## Needs Human Judgment

- **Client-controlled `dynamic_price` written into cart** — `app/Http/Controllers/Frontend/OrderController.php:22` (route routes/web.php:250). The price-tampering primitive is real (request `dynamic_price` → `Cart::add(['price'=>$price])` with no server-side comparison), but the order-persistence/checkout methods that would charge the tampered total appear stripped from the current `OrderController` (74-line stub after a malware cleanup), so a completed underpaid purchase cannot be demonstrated against HEAD. Latent critical that re-activates the moment order-store methods are restored.
- **Refund amount unvalidated / non-idempotent** — `app/Http/Controllers/Admin/Ecommerce/OrderController.php:660`/`:692`. `refund()`/`refund_two()` take `$request->amount` and credit `wallate` with no clamp vs order total and no double-refund guard. Split on whether admin-gating (staff are also `role_id==1`) makes this an exploitable trust-boundary issue or an admin-facing correctness/idempotency bug. The replayable `refund_two()` re-credit is a genuine defect regardless.
- **Product store() saves pdf/video/video_thumb/files with no type validation** — `app/Http/Controllers/Admin/Ecommerce/ProductController.php:386` (and :760/:954/:1204). Unvalidated client-extension uploads to webroot are confirmed, but the route is admin-only (`role_id==1`, staff included). Split on whether admin→OS RCE crosses a trust boundary (high) or is super-admin-only defense-in-depth (low). Note the lower-privilege Vendor variant (already confirmed High above) is unambiguous.
- **Partial-payment approval lacks idempotency** — `app/Http/Controllers/Admin/Ecommerce/OrderController.php:531`. `partialStatus($id,1)` re-adds the partial amount on replay (GET, no prior-status check), able to mark an order fully paid without funds. Split on whether the admin-only gate reduces this to a self-inflicted accounting bug (per-row clamp limits each row, but order-level can still read fully paid).
- **Undefined `$id` in `refund()` else-branch** — `app/Http/Controllers/Admin/Ecommerce/OrderController.php:673`. Real typo (`$id` should be `$order->id`); `multi_order.order_id` is NOT NULL so it updates zero rows. Disputed impact: reviewers note the else-branch only runs on re-refund of an already-refunded order and PHP 8 throws before the wallet credit, bounding harm to near-zero. Admin-gated correctness fix.
- **Email-change OTP short and not rate-limited** — `app/Http/Controllers/Frontend/AccountController.php:58`. `rand(9999,99999)` (~90k space), no throttle/lockout, static across guesses, loose `==`. Split on impact: the write is self-scoped (attacker changes their OWN email to an unowned address), so no cross-account compromise — real hardening gap, disputed as an exploitable vulnerability.
- **Unvalidated qty on cart array-update path** — `app/Core/ShoppingCart/CartItem.php:331` (route routes/web.php:132). `updateFromArray` assigns qty with no `is_numeric` check, allowing fractional/non-numeric qty into money math and a potential `DivisionByZeroError`. Split because the `qty<=0` guard (`Cart.php:218`) and PHP 8 `TypeError` semantics blunt the named zero/non-numeric paths, the cart is session-scoped, and the order-store sink is currently stripped — net self-inflicted 500 / minor corruption.
- **TLS verification disabled (low-severity duplicate)** — `app/Http/Controllers/Admin/Ecommerce/OrderController.php:755`/`:612`. Same defect as the confirmed Medium TLS finding; one reviewer marks it not-real as an application-reachable flaw (requires MITM, admin-gated trigger). Folded into the Medium finding; listed here for the split verdict.

## Coverage & Gaps

**Reviewed:** First-party code under `app/`, `routes/`, `config/`, `database/`, and `resources/views/`. Coverage spanned the vendor portal (withdrawals, orders, products, custom/POS orders), frontend account/auth flows (login, SMS recovery, email-change OTP, avatar/contact/ads/blog uploads), admin e-commerce controllers (orders, refunds, partial payments, product catalog, fraud_checker), the cart core (`app/Core/ShoppingCart/`), route wiring (`routes/web.php`, `routes/vendor.php`, `routes/admin.php`), Blade output sinks, and committed secrets in git history (`bootstrap/cache/config.php`).

**Gaps and areas warranting deeper manual review:**
- **Order persistence / payment reconciliation:** `Frontend/OrderController` is a stripped stub at HEAD (documented malware cleanup), so the uddoktapay `webhook` handler and `orderStore*` methods referenced by routes and checkout views could not be verified for signing-secret validation or paid-amount-vs-order-total reconciliation. This is the single highest-value area to re-review once those methods are restored — the calibration treats missing webhook signature/amount checks as CRITICAL, and the `dynamic_price` cart-tampering chain becomes live through this path.
- **Deployment-dependent RCE:** All file-upload RCE conclusions assume the production web server executes `.php` under `public/uploads/` (consistent with the in-repo `.htaccess`/cPanel `AddHandler`), which could not be confirmed against the live host. The stored-XSS (`.html`/`.svg`) consequences hold regardless.
- **Secret liveness:** The committed DB password, APP_KEY, BDCourier, and FCM keys are confirmed present in source/history but were not validated as currently active against their live services; rotation is required regardless.
- **Admin/staff privilege model:** Several disputed findings hinge on staff being provisioned as `role_id==1` (full admin) with no sub-admin tier (`StafController.php:44`). A deliberate role/permission redesign would change the risk rating of the admin-gated refund, partial-payment, and product-upload findings — worth a dedicated review.
- **Broader IDOR sweep:** The `Model::find($request->id)` / `findOrFail($id)`-without-ownership-scope pattern is pervasive; controllers beyond those enumerated (e.g. other frontend and admin CRUD) likely share it and merit a systematic grep-and-audit.