ARG PHP_VERSION
FROM php:${PHP_VERSION}-apache
RUN docker-php-ext-install pdo_mysql\
    && a2enmod rewrite \
    && apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql
EXPOSE 80
COPY docker/local.conf /etc/apache2/conf-enabled