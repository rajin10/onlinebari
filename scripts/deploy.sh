#!/usr/bin/env bash
set -euo pipefail

PHP=/opt/alt/php85/usr/bin/php

COMPOSER="$PHP $(which composer 2>/dev/null || echo composer)"

echo "[deploy] PHP: $PHP"
echo "[deploy] Installing PHP dependencies..."
$COMPOSER install --no-dev --no-interaction --optimize-autoloader

echo "[deploy] Caching config, routes, views..."
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
$PHP artisan event:cache

echo "[deploy] Running migrations..."
$PHP artisan migrate --force

echo "[deploy] Restarting queue workers..."
$PHP artisan queue:restart

echo "[deploy] Creating storage symlink (safe if already exists)..."
$PHP artisan storage:link 2>/dev/null || true

echo "[deploy] Done."
