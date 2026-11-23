FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    ca-certificates \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Tối ưu cache: copy dependency trước để lần sau deploy siêu nhanh
COPY composer.json composer.lock /app/
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . /app

RUN chmod -R 777 /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD bash -c "chmod -R 777 /app/storage /app/bootstrap/cache && php artisan storage:link || true && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force || true && php artisan db:seed --force || true && php artisan db:seed --class=SettingSeeder --force || true && php artisan serve --host 0.0.0.0 --port \${PORT:-8000}"
