ARG PHP_VERSION
FROM php:${PHP_VERSION}-apache
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN docker-php-ext-install pdo_mysql\
    && a2enmod rewrite \
    && apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
EXPOSE 80
COPY docker/local.conf /etc/apache2/conf-enabled