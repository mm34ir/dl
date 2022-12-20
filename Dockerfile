USER root
FROM php:7.2
RUN apt-get update && apt-get install -y \
    build-essential \
    locales \
    git \
    unzip \
    zip \
    curl
COPY . /var/www
COPY --chown=www:www . /var/www
EXPOSE 80
