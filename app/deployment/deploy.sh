#!/bin/bash
php /var/www/html/secretlab/artisan migrate --force
php /var/www/html/secretlab/artisan config:cache
php /var/www/html/secretlab/artisan config:clear
cd /var/www/html/secretlab
./vendor/bin/phpunit
