# PHP with required extensions
FROM php:8.3-cli

# Install system packages
RUN apt-get update && apt-get install -y \
    unzip git zip curl libicu-dev libpq-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_pgsql pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy app
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose Symfony public folder
EXPOSE 10000

# Run Symfony with built-in PHP server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
