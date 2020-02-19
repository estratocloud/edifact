FROM php:7.3-cli

RUN apt-get update -yqq && apt-get install -yqq openssh-server
RUN apt-get install -yqq git unzip

RUN pecl install xdebug
# RUN docker-php-ext-enable xdebug

RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/memory_limit.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

WORKDIR /var/app

CMD ["bash", "--login"]
