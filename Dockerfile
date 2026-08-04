ARG PHP_VERSION=8.0
FROM php:${PHP_VERSION}-cli

RUN apt update && apt install -y libtool git zip
COPY --from=ghcr.io/php/pie:bin /pie /usr/local/bin/pie
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN pie install pecl/pcov && docker-php-ext-enable pcov

RUN echo "memory_limit = -1" > /usr/local/etc/php/conf.d/edifact.ini

WORKDIR /app
RUN git config --global --add safe.directory /app
