#!/bin/bash

set -e

env

if [[ -n "$1" ]]; then
    exec "$@"
else
   
    composer install
    php artisan migrate 
    php artisan key:generate
    php artisan db:seed
    chmod -R 777 app
    chmod -R 777 resources
    chmod -R 777 storage/framework/sessions*
    chmod -R 777 storage/framework/views*
    chmod -R 777 storage/logs/laravel.log
    chmod -R 777 storage/framework/cache/data*
    chmod -R 777 bootstrap/cache*
    exec apache2-foreground
fi
