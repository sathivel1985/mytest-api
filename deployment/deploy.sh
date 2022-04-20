#!/bin/bash
php /var/www/secretlab/artisan migrate --force
php /var/www/secretlab/artisan config:cache
php /var/www/secretlab/artisan config:clear
cd /var/www/secretlab
./vendor/bin/phpunit
