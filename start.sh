#!/bin/bash
echo "Iniciando servidor en puerto $PORT"
php artisan migrate --force
echo "Migraciones completadas"
php -S 0.0.0.0:$PORT -t public
echo "Servidor iniciado"
