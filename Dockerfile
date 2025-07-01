# Base image with PHP and necessary extensions
FROM php:8.3-cli

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    unzip git zip curl libicu-dev libonig-dev libxml2-dev libzip-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install intl pdo pdo_sqlite pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside container
WORKDIR /app

# Copy your app source into the container
COPY . .

# Install PHP dependencies without dev, optimize for production
RUN composer install --no-dev --optimize-autoloader

# Expose the public port used by PHP server
EXPOSE 10000

# Start Symfony using built-in PHP web server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
