# Imagen base
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Resuelve problemas de Git
RUN git config --global --add safe.directory /var/www/html

# Copiar el proyecto (excluyendo node_modules y vendor)
COPY . .

# Instalar dependencias y optimizar
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && php artisan key:generate \
    && chown -R www-data:www-data storage \
    && chmod -R 775 storage