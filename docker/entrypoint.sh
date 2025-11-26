#!/bin/bash
set -e

cd /var/www/html

composer install --no-interaction

php artisan key:generate

chmod -R 777 storage bootstrap/cache

exec apache2-foreground