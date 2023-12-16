ARG PHP_VERSION
ARG APACHE_DOCUMENT_ROOT
FROM php:${PHP_VERSION}-apache
RUN docker-php-ext-install pdo_mysql\
    && a2enmod rewrite \
    && apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql
EXPOSE 80
CMD ["apache2-foreground"]
