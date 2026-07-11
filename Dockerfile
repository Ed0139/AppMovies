FROM php:8.3-apache-bookworm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev

# Extensiones de PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip

# Composer
COPY --from=composer:latest \
    /usr/bin/composer \
    /usr/bin/composer

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Configurar Apache para Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY . .

RUN composer install

RUN chown -R www-data:www-data \
storage bootstrap/cache

EXPOSE 80