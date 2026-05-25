FROM php:8.2-cli

# Install system dependencies for GD
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install pdo pdo_mysql mysqli fileinfo mbstring gd

WORKDIR /app
COPY . /app

# Railway injects $PORT at runtime
CMD php -S 0.0.0.0:$PORT -t /app
