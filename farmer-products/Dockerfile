FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts \
    --ignore-platform-req=ext-pgsql

FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json .npmrc ./

RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js tailwind.config.js postcss.config.js ./

RUN npm run build

FROM php:8.3-apache-bookworm AS runtime

ENV APP_ENV=production
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && a2enmod rewrite headers expires \
    && printf 'ServerName localhost\n' > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername \
    && rm -rf /var/lib/apt/lists/*

COPY --from=vendor /app/vendor ./vendor
COPY . .
COPY --from=frontend /app/public/build ./public/build
COPY railway/apache-vhost.conf /etc/apache2/sites-available/000-default.conf.template

RUN chmod +x ./railway/*.sh \
    && mkdir -p \
        storage/app/public \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache \
    && php artisan package:discover --ansi

CMD ["./railway/start-app.sh"]
