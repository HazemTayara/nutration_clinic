FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
zip unzip git curl libzip-dev libicu-dev libonig-dev

RUN docker-php-ext-install pdo pdo_pgsql mbstring bcmath zip intl

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN chown -R www-data:www-data storage bootstrap/cache

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

CMD sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf && apache2-foreground