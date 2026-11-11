# ---- Etapa 1: compilar los assets (Vite + Tailwind) ----
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- Etapa 2: aplicación Laravel (PHP 8.4) ----
FROM php:8.4-cli

RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pdo_pgsql pgsql

RUN apt-get update && apt-get install -y --no-install-recommends \
        unzip zip git libzip-dev \
    && docker-php-ext-install zip \
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

# Autoload optimizado + paquetes + symlink de storage
# (las migraciones NO van aquí: durante el build no existen las variables
#  de entorno de Render, así que apuntarían a la BD equivocada)
RUN mkdir -p database storage/app/public storage/framework/cache/data \
        storage/framework/sessions storage/framework/views \
        storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database \
    && composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi \
    && php artisan storage:link --force

EXPOSE 8000

# Al arrancar: migrar y sembrar contra la BD de Render (Postgres), luego servir.
# El seeder es idempotente (firstOrCreate), seguro en cada reinicio.
CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
