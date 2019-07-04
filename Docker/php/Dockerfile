FROM php:7.3-fpm

LABEL maintainer="Daniil Karnaukhov <kdswto@gmail.com>"

# install utils
RUN apt-get update && apt-get install -y --fix-missing \
    zlib1g-dev \
    libzip-dev \
    wget \
    nano \
    vim


# install zip extension
RUN docker-php-ext-install zip pdo pdo_mysql

## Install composer globally
RUN echo "Install composer globally"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer