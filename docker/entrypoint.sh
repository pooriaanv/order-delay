#!/usr/bin/env sh

#cp .env.example .env
#
#composer install

chown -R root:www-data /var/www/html/storage
chown -R root:www-data /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage

#php artisan optimize

php-fpm
