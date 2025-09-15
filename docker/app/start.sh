#!/usr/bin/env sh
set -e

# install php dependencies if vendor missing
if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader || true
fi

# install node dependencies if node_modules missing
if [ ! -d node_modules ]; then
  npm install || true
fi

# run migrations
php artisan key:generate || true
php artisan migrate --force || true

npm run dev &
php-fpm
