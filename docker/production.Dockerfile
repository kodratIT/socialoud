FROM php:8.4-fpm-bookworm AS php-base

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/tmp/composer \
    COMPOSER_MEMORY_LIMIT=384M

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j2 bcmath curl exif gd intl mbstring mysqli opcache pdo_mysql soap xml zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
RUN rm -rf vendor node_modules \
    && composer install --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader --no-scripts \
    && php artisan package:discover --ansi \
    && php artisan cms:publish:assets \
    && mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

FROM node:22-bookworm-slim AS assets

WORKDIR /build
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts --no-audit --no-fund
COPY . .
COPY --from=php-base /var/www/html/public/vendor /build/public/vendor
RUN NODE_OPTIONS=--max-old-space-size=384 npm run production

FROM php-base AS app

COPY --from=assets /build/public /var/www/html/public
COPY docker/php-fpm/php.ini /usr/local/etc/php/conf.d/zz-production.ini
COPY docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/zz-production.conf
RUN test -d public/vendor \
    && test -d public/themes

CMD ["php-fpm", "-F"]

FROM nginx:1.27-alpine AS nginx

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --from=app /var/www/html/public /var/www/html/public
