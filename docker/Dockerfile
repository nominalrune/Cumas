ARG PHP_VERSION
FROM php:${PHP_VERSION}-apache
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY docker/local.conf /etc/apache2/conf-enabled
RUN apt-get update && apt-get install -y libpq-dev libc-client-dev libkrb5-dev \
    && pecl install imap mailparse \
    && docker-php-ext-install pdo_mysql pdo_pgsql \
    && docker-php-ext-enable imap mailparse \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite
EXPOSE 80