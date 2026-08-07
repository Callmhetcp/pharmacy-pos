FROM php:8.3-cli

# Install system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*


# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# Set working directory
WORKDIR /var/www


# Copy application
COPY . .


# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader


# Permissions
RUN chmod -R 775 storage bootstrap/cache


# Expose Render port
EXPOSE 10000


# Start Laravel
CMD php artisan storage:link || true && \
    php artisan migrate:fresh --seed --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-10000}