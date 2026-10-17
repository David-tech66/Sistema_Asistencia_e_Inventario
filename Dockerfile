# ---- Etapa 1: compilar los assets (Vite + Tailwind) ----
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- Etapa 2: aplicación Laravel (PHP 8.3) ----
FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        unzip zip libonig-dev libzip-dev \
    && docker-php-ext-install mbstring zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dependencias PHP primero (capa cacheada entre deploys)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction

# Código de la aplicación
COPY . .

# Assets compilados por la etapa de Node
COPY --from=assets /app/public/build public/build

# Autoload optimizado + paquetes + symlink de storage + BD con datos iniciales
RUN mkdir -p database storage/app/public storage/framework/cache/data \
        storage/framework/sessions storage/framework/views \
        storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database \
    && composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi \
    && php artisan storage:link --force \
    && php artisan migrate --force \
    && php artisan db:seed --force

EXPOSE 8000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
