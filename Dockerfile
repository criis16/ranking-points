FROM php:8.1-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

# INSTALL COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

WORKDIR /app

RUN composer install
