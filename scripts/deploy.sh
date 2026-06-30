#!/usr/bin/env bash
# Post-deploy steps run ON the Hostinger server by the GitHub Actions workflow.
# By the time this runs the workflow has already reset the app dir to
# origin/main and rsynced the freshly built public/build assets.
set -euo pipefail

# Match the PHP version the domain serves over the web (ea-php83 / 8.3).
PHP=/opt/alt/php83/usr/bin/php
COMPOSER="$PHP $(command -v composer 2>/dev/null || echo /usr/local/bin/composer)"

echo "[deploy] PHP: $($PHP -v | head -1)"

echo "[deploy] Installing PHP dependencies..."
$COMPOSER install --no-dev --no-interaction --optimize-autoloader --no-progress

# storage:link can't be used: symlink()/exec() are disabled on this host, so
# Laravel's command fails. Create the link directly instead (idempotent).
echo "[deploy] Ensuring public/storage symlink..."
[ -L public/storage ] || ln -sfn ../storage/app/public public/storage

echo "[deploy] Running migrations..."
$PHP artisan migrate --force

echo "[deploy] Caching config, routes, views, events..."
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
$PHP artisan event:cache

echo "[deploy] Restarting queue workers (no-op while QUEUE_CONNECTION=sync)..."
$PHP artisan queue:restart || true

echo "[deploy] Fixing storage permissions..."
chmod -R ug+rwX storage bootstrap/cache

echo "[deploy] Done: $(git rev-parse --short HEAD)"
