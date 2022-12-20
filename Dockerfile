FROM php:7.4-apache
COPY . /var/www/html
EXPOSE 80
USER root
