FROM php:8.2-cli-alpine

WORKDIR /app

# Install system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /app

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create non-root user
RUN addgroup -g 1000 appuser && \
    adduser -D -u 1000 -G appuser appuser

USER appuser

CMD ["php", "-a"]
