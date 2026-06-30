# Eureka

A multi-vendor e-commerce platform built on Laravel 11. It ships a customer storefront, a
multi-vendor seller portal, and a full admin back office — with built-in order management, a POS,
live chat, marketing tools, and fraud protection. Payment and delivery integrations are tuned for
the Bangladesh market (UddoktaPay, Steadfast Courier, BDCourier fraud lookup).

---

## Features

**Storefront**
- Product catalog with categories, sub-categories, mini-categories, brands, collections, tags,
  attributes, colors, and sizes
- Cart, wishlist, reviews, and a Buy-Now flow
- Guest, minimal, and full checkout; partial payments; downloadable products
- Incomplete-lead and active-visitor tracking
- Blogs (with comments), campaigns, contact forms, newsletter subscriptions

**Multi-vendor**
- Vendor accounts, dashboards, product and order management
- Commissions, withdrawals, and per-vendor reporting

**Admin back office**
- Catalog, order, customer, coupon, banner/slider, and homepage management
- Point of Sale (POS) and custom orders
- Staff/roles, support tickets, settings, and reporting/exports

**Operations & security**
- Live chat / SMS messaging between customers and staff
- Fraud checker (local order history + BDCourier API, with graceful fallback)
- IP block list and duplicate-order throttling
- Excel import/export

---

## Tech stack

| Area | Technology |
|------|-----------|
| Backend | PHP 8.2+, Laravel 11 |
| Frontend | Blade, Vite 8, Tailwind CSS v4, Alpine.js |
| Database | MySQL (default) / SQLite (local dev) |
| Search | Laravel Scout |
| Auth | Laravel UI + Socialite (social login) |
| Testing | Pest |
| Formatting | Laravel Pint |
| Integrations | UddoktaPay (payments), Steadfast Courier (delivery), BDCourier (fraud), Maatwebsite Excel, Intervention Image |

---

## Requirements

- PHP **8.2+** with the standard Laravel extensions
- Composer
- Node.js **18+** and npm
- MySQL 8 (or SQLite for local development)

---

## Installation

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Configure your database in .env, then migrate
php artisan migrate

# 4. Storage symlink for uploaded media
php artisan storage:link
```

---

## Running locally

```bash
# Everything at once: server + queue worker + logs + Vite
composer dev

# …or run the pieces yourself
php artisan serve
npm run dev
```

The app runs at `http://localhost:8000`. Build assets for production with `npm run build`.

---

## Testing & formatting

```bash
composer test          # run the Pest suite (clears config first)
vendor/bin/pint        # format code (PSR-12) before committing
```

---

## Configuration

Core settings live in `.env` (see `.env.example`):

- **App:** `APP_NAME`, `APP_URL`, `APP_ENV`, `APP_DEBUG`
- **Database:** `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- **Mail:** `MAIL_*`
- **Queue / cache / session:** `QUEUE_CONNECTION`, `CACHE_STORE`, `SESSION_DRIVER` (default to `database`)
- **Social login:** Socialite provider keys

> **Third-party API keys** (UddoktaPay, Steadfast Courier, BDCourier fraud checker, SMS) are managed
> at runtime in **Admin → Settings**, not in `.env`. The fraud checker degrades gracefully to local
> order history if the external API is unavailable or rate-limited.

The default queue and cache drivers are database-backed, so a queue worker must be running for
queued jobs to process (`composer dev` starts one for you).

---

## Project structure

```
app/
├── Http/Controllers/
│   ├── Admin/         # admin back office (+ Admin/Ecommerce)
│   ├── Frontend/      # customer storefront
│   ├── Vendor/        # multi-vendor seller portal
│   └── Auth/          # authentication
├── Models/            # ~52 Eloquent models ($guarded = ['id'] convention)
├── Services/          # SteadfastCourierService
├── Library/           # UddoktaPay payment integration
└── Notifications/

routes/
├── web.php            # storefront routes
├── admin.php          # admin routes
├── vendor.php         # vendor routes
└── api.php            # API routes

resources/views/       # Blade templates
database/migrations/   # schema
```

---

## Working with AI coding agents

All rules and project context for AI coding agents live in a single source of truth —
**[AGENTS.md](AGENTS.md)**. Most agents (Codex, Cursor, Windsurf, Cline, Copilot in VS Code/CLI)
read it natively; the rest load it through a thin pointer (`CLAUDE.md`, `GEMINI.md`,
`.aider.conf.yml`, `.github/copilot-instructions.md`). See **[docs/ai-agents/](docs/ai-agents/README.md)**
for the coverage matrix and token-cost notes.

---

## License

Proprietary. All rights reserved.
