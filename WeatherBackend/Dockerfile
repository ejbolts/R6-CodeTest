#
# Stage 1: Composer Builder - Install PHP dependencies
#
FROM composer:2 AS composer_builder

WORKDIR /app

# Copy only the necessary files to leverage Docker's layer caching
COPY database/ ./database
COPY composer.json composer.lock ./

# Install dependencies without dev packages and without running scripts yet
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist


#
# Stage 2: Application Base - The final production image
#
FROM php:8.3-fpm-alpine AS production_image

# Set working directory
WORKDIR /app

# Install system dependencies required by Laravel and Nginx
# Supervisor is used to run both Nginx and PHP-FPM processes
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    zip \
    oniguruma-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# Install required PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    zip

# Copy Composer's installed dependencies from the builder stage
COPY --from=composer_builder /app/vendor ./vendor

# Copy the rest of the application code
COPY . .

# Set correct file permissions for Laravel
# The www-data user needs to own storage and bootstrap/cache to write logs and cache files
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Copy Nginx and Supervisor configurations into the container
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 80 to the outside world
EXPOSE 80

# The main command to start the container.
# Supervisor will start and manage both Nginx and PHP-FPM.
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]