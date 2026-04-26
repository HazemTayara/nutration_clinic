FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
zip unzip git curl libzip-dev libpng-dev libicu-dev libonig-dev

RUN docker-php-ext-install \
pdo \
pdo_mysql \
mbstring \
bcmath \
zip \
intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs