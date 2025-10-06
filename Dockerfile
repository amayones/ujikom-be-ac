# Dockerfile
FROM php:8.2-fpm

# Install system deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    default-mysql-client netcat-openbsd libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set PHP memory limit for composer
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

WORKDIR /var/www

# Copy composer files first
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy rest of application
COPY . .

# Run post-install scripts
RUN composer dump-autoload --optimize

# Pastikan folder penting ada & permissions
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000
ENTRYPOINT ["entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
