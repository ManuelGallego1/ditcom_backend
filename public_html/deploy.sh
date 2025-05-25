#!/bin/bash

# Ir al directorio correcto del proyecto Laravel
cd /home/u800814029/domains/ditcombackend.amcdevcode.com || exit 1

# Instalar dependencias PHP en modo produccion
composer install --no-dev --optimize-autoloader

# Ejecutar migraciones sin confirmacion
php artisan migrate --force

# Limpiar y cachear configuracion
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
