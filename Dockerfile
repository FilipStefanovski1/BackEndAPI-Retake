FROM php:8.2-apache

# Install system dependencies (PHP + PostgreSQL + Node.js)
RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    unzip \
    zip \
    sqlite3 \
    libsqlite3-dev \
    libpq-dev \
    git \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql zip

# Install Node.js (for Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Laravel files
COPY . /var/www/html

# Change the web root to the Laravel public directory
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Copy Composer and install dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Install npm packages and build frontend
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
