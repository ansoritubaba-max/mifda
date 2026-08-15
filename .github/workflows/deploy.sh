#!/bin/sh
set -e

# GANTI path di bawah sesuai folder LIVE Mifda di server (cek lewat
# Terminal cPanel: `pwd` pas kamu udah `cd` ke folder yang isinya index.php
# Laravel-nya).
cd /home/GANTI_USERNAME/mifda.my.id

git pull origin main
composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deploy Mifda selesai: $(date)"
