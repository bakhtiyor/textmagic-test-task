# Stage 1: PHP base image
FROM php:8.1-fpm AS base
# Set working directory
WORKDIR /var/www

FROM base as main
# Set COMPOSER_ALLOW_SUPERUSER=1 if you want to allow plugins to run as root/super user
ENV COMPOSER_ALLOW_SUPERUSER=1
# Install required dependencies for Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip
# Copy Composer from composer stage
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . .
RUN echo "memory_limit=4G" >> /usr/local/etc/php/php.ini; \
    rm -rf var/cache/* var/log/* ;\
    composer install

FROM main as dev
# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev
# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Stage 3: Final image
FROM base AS prod
COPY --from=main /var/www .