FROM php:7.2.3-apache

LABEL maintainer="fatal.error.27@gmail.com"

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
