# ========================================================
# STAGE 1 - BUILD WITH PHP 8.2 (Composer inside)
# ========================================================
FROM php:8.2-fpm AS builder

RUN apt-get update && apt-get install -y \
    unzip curl git \
    libzip-dev libicu-dev libpng-dev libxml2-dev \
    libjpeg-dev libfreetype6-dev libonig-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip intl gd mysqli pdo pdo_mysql mbstring exif

RUN curl -sS https://getcomposer.org/installer | php \
    -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
#COPY composer.json composer.lock ./
#RUN composer install --no-dev --prefer-dist --no-interaction


# ========================================================
# STAGE 2 - PHP-FPM FINAL RUNTIME
# ========================================================
FROM php:8.2-fpm AS php-fpm

RUN apt-get update && apt-get install -y \
    unzip libzip-dev libicu-dev \
    libpng-dev libxml2-dev libjpeg-dev libfreetype6-dev libonig-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip intl gd mysqli pdo pdo_mysql mbstring exif

WORKDIR /var/www/html

#COPY . .
#COPY --from=builder /app/vendor ./vendor

#RUN mkdir -p writable/cache writable/logs writable/session public/uploads \
#    && chown -R www-data:www-data writable public/uploads \
#    && chmod -R 775 writable public/uploads

EXPOSE 9000
CMD ["php-fpm"]


# ========================================================
# STAGE 3 - NGINX SERVER
# ========================================================
FROM nginx:alpine AS nginx

COPY ./nginx.conf /etc/nginx/nginx.conf

# COPY project ke folder nginx
COPY . /var/www/html

# Fix permission
RUN chown -R nginx:nginx /var/www/html \
    && chmod -R 775 /var/www/html/writable

WORKDIR /var/www/html

