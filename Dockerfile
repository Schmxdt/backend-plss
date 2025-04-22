FROM php:8.3-fpm

# Dependências
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip unzip curl \
    libonig-dev \
    libxml2-dev \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install
