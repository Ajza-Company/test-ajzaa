FROM php:8.2-cli-alpine

RUN apk add --no-cache \
    linux-headers \
    git \
    libzip-dev \
    zip \
    mysql-client \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install \
    sockets \
    pcntl \
    zip \
    pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize

CMD ["php", "artisan", "reverb:start", "--host=0.0.0.0"]
