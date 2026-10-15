# SIGMA - Laravel 12 / PHP-FPM / PostgreSQL

FROM node:22-bookworm-slim AS frontend
WORKDIR /var/www/html
COPY package.json package-lock.json ./
RUN PUPPETEER_SKIP_DOWNLOAD=true npm ci
COPY . .
RUN npm run build

FROM node:22-bookworm-slim AS node-runtime

FROM php:8.4-fpm-bookworm AS app
ENV DEBIAN_FRONTEND=noninteractive \
    PUPPETEER_CACHE_DIR=/opt/puppeteer \
    XDG_CONFIG_HOME=/tmp/.chromium \
    XDG_CACHE_HOME=/tmp/.chromium

COPY --from=node-runtime /usr/local/ /usr/local/

RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates curl git unzip zip dpkg-dev \
    libpq-dev libicu-dev libzip-dev libpng-dev libjpeg62-turbo-dev \
    libfreetype6-dev libonig-dev libxml2-dev libldap2-dev libssl-dev \
    libcurl4-openssl-dev \
    fonts-liberation libasound2 libatk-bridge2.0-0 libatk1.0-0 libcairo2 \
    libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libglib2.0-0 \
    libgtk-3-0 libnspr4 libnss3 libpango-1.0-0 libpangocairo-1.0-0 \
    libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 \
    libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 \
    libxshmfence1 xdg-utils \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure ldap --with-libdir="lib/$(dpkg-architecture -qDEB_HOST_MULTIARCH)" \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql mbstring bcmath intl zip gd xml ldap opcache

RUN sed -i 's/^listen = 9000$/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/docker.conf \
    && sed -i -e 's/^pm.max_children = 5$/pm.max_children = 8/' \
       -e 's/^pm.min_spare_servers = 1$/pm.min_spare_servers = 2/' \
       -e 's/^pm.max_spare_servers = 3$/pm.max_spare_servers = 4/' \
       /usr/local/etc/php-fpm.d/www.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY package.json package-lock.json ./
RUN npm ci --omit=dev

COPY . .
COPY --from=frontend /var/www/html/public/build ./public/build
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && php artisan package:discover --ansi \
    && php artisan storage:link \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]

FROM nginx:1.27-alpine AS web
COPY --from=app /var/www/html/public /var/www/html/public
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
