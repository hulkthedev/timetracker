FROM php:7.2.3-apache

LABEL maintainer="alexej.beirith@arvato.com"

COPY . /var/www/html

RUN usermod -u 1000 www-data \
    && chown -R www-data:www-data /var/www/html