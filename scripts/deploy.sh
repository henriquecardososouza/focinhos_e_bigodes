#!/usr/bin/env bash
echo "Running composer"
#composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html
composer update

echo "Seeding database"
php artisan db:seed --force

echo "Installing libraries"
npm install

echo "Building assets"
npm run build

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force
