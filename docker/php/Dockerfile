FROM php:8.4-fpm

# Instalar dependencias del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    # Instalar extensiones de PHP
    && docker-php-ext-install \
        pdo_pgsql \
        zip \
        intl \
        bcmath \
    # Limpiar la cache de apt
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Puerto de PHP-FPM
EXPOSE 9000