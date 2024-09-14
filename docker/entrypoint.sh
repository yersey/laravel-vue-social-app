#!/bin/bash

php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan db:seed

exec "$@"