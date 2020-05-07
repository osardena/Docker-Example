# PHP dependency manager
FROM composer:1.9.3 AS composer

FROM php:7.2.29-apache

# Copies dependency manager into PHP image
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Install RabbitMQ PHP client library
RUN apt-get -qq update && apt-get -qq -y install \
    git \
    libzip-dev \
    unzip \
    && docker-php-ext-install \
       sockets \
       zip \
    && composer require php-amqplib/php-amqplib \
    && composer install

# Copy html files from current directory into container filesystem
COPY ./html /var/www/html/