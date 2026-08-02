FROM php:8.3-cli-alpine

# Install required system dependencies & PHP extensions for Laravel 13
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    mysql-client

RUN docker-php-ext-install pdo_mysql mbstring intl zip bcmath opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Create storage directory structure & fix permissions
RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache

EXPOSE 8080

# Production launch command for Laravel
CMD ["sh", "-c", "php artisan config:clear && php artisan route:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
