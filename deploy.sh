#! /bin/bash

#pull latest changes from repo
git stash
git pull

#update modules
cd /var/www/tetfund-bi-portal/vendor/hasob/hasob-foundation-core-bs-5
git pull

cd /var/www/tetfund-bi-portal/vendor/tetfund/tetfund-bims-module
git pull

cd /var/www/tetfund-bi-portal
git stash pop stash@{0}

#change file permission
sudo chmod -R 777 app
sudo chmod -R 777 resources
sudo chmod -R 777 storage/framework/sessions*
sudo chmod -R 777 storage/framework/views*
sudo chmod -R 777 storage/logs/laravel.log
sudo chmod -R 777 storage/framework/cache/data*
sudo chmod -R 777 bootstrap/cache*


# run migrations
php artisan migrate --force

#clear cache
php artisan optimize:clear

#clear route 
php artisan route:clear

#restart webserver
systemctl restart apache2