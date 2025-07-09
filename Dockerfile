# Start from official PHP + Apache image
FROM php:8.2-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    zip \
    sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Laravel files
COPY . /var/www/html

# Install Composer (dependency manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions for storage
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 80 and run Apache
EXPOSE 80
CMD ["apache2-foreground"]
