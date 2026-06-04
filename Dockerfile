FROM php:7.4-fpm-alpine AS base

# Install dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd bcmath opcache pcntl zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer and package files first for layer caching
COPY composer.json composer.lock package.json package-lock.json ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
RUN npm install

# Copy application files
COPY . .

# Set permissions for the web directory
RUN chown -R www-data:www-data /var/www/html

# Compile frontend assets
RUN npx mix --production

# Complete composer install
RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi || true

# Create required storage directories
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
