FROM php:8.4-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        zip \
        intl \
        bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar el entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Dar permisos de ejecución
RUN chmod +x /usr/local/bin/entrypoint.sh

# Directorio de trabajo
WORKDIR /var/www/html

# Usar el entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000

