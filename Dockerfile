FROM php:8.2-cli

# Install system dependencies for GD and mbstring
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (fileinfo & pdo are bundled, no separate install needed)
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install pdo_mysql mysqli mbstring gd

WORKDIR /app
COPY . /app

# Railway injects $PORT at runtime
CMD php -S 0.0.0.0:$PORT -t /app
