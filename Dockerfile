ARG PHP_VERSION=8.0
FROM php:${PHP_VERSION}-cli-alpine

RUN apk add --no-cache git zip $PHPIZE_DEPS

RUN pecl install pcov && docker-php-ext-enable pcov

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN echo "memory_limit = -1" > /usr/local/etc/php/conf.d/edifact.ini

WORKDIR /app
RUN git config --global --add safe.directory /app
