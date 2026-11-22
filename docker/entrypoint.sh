#!/bin/sh
# Dijalankan setiap container "app" start.
# Hanya melakukan persiapan ketika command-nya adalah server Apache,
# sehingga perintah lain (mis. `docker compose run --rm app php artisan tinker`)
# tidak ikut memicu migrasi.
set -e

cd /var/www/html

if [ "$1" = "apache2-foreground" ]; then
    if [ -z "${APP_KEY}" ]; then
        echo "[entrypoint] ERROR: APP_KEY kosong. Isi APP_KEY di file .env (lihat .env.example)." >&2
        exit 1
    fi

    if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
        echo "[entrypoint] Menjalankan migrasi database..."
        runuser -u www-data -- php artisan migrate --force
    fi

    if [ "${RUN_SEEDERS:-false}" = "true" ]; then
        echo "[entrypoint] Menjalankan seeder..."
        runuser -u www-data -- php artisan db:seed --force
    fi
fi

exec "$@"
