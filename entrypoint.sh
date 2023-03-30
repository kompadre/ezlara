#!/usr/bin/env sh
php artisan migrate:fresh --seed
php artisan serve --host=0.0.0.0
