#!/bin/bash
set -e

echo "Aguardando MySQL estar pronto..."
sleep 10

echo "Instalando dependências do Composer..."
composer install --no-interaction

echo "Gerando APP_KEY se necessário..."
if ! grep -q "^APP_KEY=base64:" /app/.env 2>/dev/null; then
    php artisan key:generate --force
fi

echo "Executando migrations..."
php artisan migrate --force

echo "Iniciando aplicação Laravel..."
php artisan serve --host=0.0.0.0 --port=8000
