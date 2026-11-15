# syntax=docker/dockerfile:1

# =============================================================================
# KirimCepat - Dockerfile multi-stage (Laravel 13 / PHP 8.4 + Apache)
#
#   base          PHP 8.4 + Apache + pdo_mysql (dasar untuk stage lain)
#   composer-base base + composer/git/unzip (hanya dipakai saat build)
#   assets        build CSS/JS dengan Vite (Node)  -> public/build
#   test          install dependency (+dev) lalu jalankan `php artisan test`
#   vendor-prod   install dependency tanpa dev + optimize autoload
#   production    image akhir yang di-push ke Docker Hub (stage TERAKHIR = default)
#
# Contoh pemakaian:
#   docker build --target test -t kirimcepat:test .    # jalankan test (gagal = build gagal)
#   docker build -t kirimcepat:local .                 # build image production
# =============================================================================

ARG PHP_VERSION=8.4
ARG NODE_VERSION=22

# -----------------------------------------------------------------------------
# base: runtime PHP + Apache
# -----------------------------------------------------------------------------
FROM php:${PHP_VERSION}-apache AS base

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername \
    && rm -rf /var/lib/apt/lists/*

# DocumentRoot diarahkan ke /var/www/html/public (standar Laravel)
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# -----------------------------------------------------------------------------
# composer-base: base + tool untuk install dependency PHP
# -----------------------------------------------------------------------------
FROM base AS composer-base

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

# -----------------------------------------------------------------------------
# assets: compile frontend (Vite + Tailwind) -> public/build
# -----------------------------------------------------------------------------
FROM node:${NODE_VERSION}-slim AS assets

WORKDIR /app

# package-lock.json opsional: kalau ada pakai `npm ci` (reproducible), kalau belum ada `npm install`
COPY package.json package-lock.json* .npmrc ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY vite.config.js ./
COPY public ./public
COPY resources ./resources
RUN npm run build

# -----------------------------------------------------------------------------
# test: jalankan PHPUnit di dalam container (tanpa PHP/Composer di mesin Jenkins)
#       Jika ada test yang gagal -> `docker build --target test` ikut gagal.
# -----------------------------------------------------------------------------
FROM composer-base AS test

# Layer dependency dipisah agar di-cache selama composer.json/composer.lock tidak berubah
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-scripts --no-autoloader

COPY . .
RUN mkdir -p storage/framework/cache/data storage/framework/sessions \
        storage/framework/views storage/framework/testing storage/logs bootstrap/cache \
    && composer dump-autoload --optimize

# APP_KEY sementara (acak, hanya hidup selama proses test, bukan rahasia produksi)
RUN APP_KEY="base64:$(head -c 32 /dev/urandom | base64)" php artisan test

# -----------------------------------------------------------------------------
# vendor-prod: aplikasi + dependency production saja (tanpa phpunit, faker, dll.)
# -----------------------------------------------------------------------------
FROM composer-base AS vendor-prod

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-autoloader

COPY . .
RUN mkdir -p storage/framework/cache/data storage/framework/sessions \
        storage/framework/views storage/logs bootstrap/cache \
    && composer dump-autoload --no-dev --optimize

# -----------------------------------------------------------------------------
# production: image final (tanpa composer/git/node -> lebih kecil & aman)
# -----------------------------------------------------------------------------
FROM base AS production

ENV APP_ENV=production \
    APP_DEBUG=false

COPY --from=vendor-prod --chown=www-data:www-data /var/www/html ./
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p storage/framework/cache/data storage/framework/sessions \
        storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

# Healthcheck didefinisikan di compose.yaml (satu tempat saja agar mudah diganti ke /health)
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
