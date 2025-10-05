#!/bin/sh
set -e

: "${DB_HOST:=db}"
: "${DB_PORT:=3306}"

# Pastikan .env ada (jika tidak, copy dari .env.example / buat fallback)
if [ ! -f /var/www/.env ]; then
  echo ".env not found, creating from .env.example or fallback"
  if [ -f /var/www/.env.example ]; then
    cp /var/www/.env.example /var/www/.env
  else
    cat > /var/www/.env <<'EOF'
APP_NAME=Cinema
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=cinema
DB_USERNAME=laravel
DB_PASSWORD=laravel
EOF
  fi
fi

cd /var/www

echo "Waiting for database ${DB_HOST}:${DB_PORT} ..."
timeout=120
while ! nc -z "$DB_HOST" "$DB_PORT"; do
  sleep 2
  timeout=$((timeout-2))
  if [ "$timeout" -le 0 ]; then
    echo "Timed out waiting for DB"
    exit 1
  fi
done

# Generate key jika kosong
if ! grep -q '^APP_KEY=' .env || [ -z "$(grep '^APP_KEY=' .env | cut -d '=' -f2)" ]; then
  echo "No APP_KEY found — generating one..."
  php artisan key:generate --force
fi

echo "DB is up — running migrations..."
php artisan migrate --force || true
php artisan config:cache || true

exec "$@"
