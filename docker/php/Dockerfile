FROM php:7.3-fpm

LABEL maintainer="Daniil Karnaukhov <kdswto@gmail.com>"

# install utils
RUN apt-get update -y && apt-get install -y --fix-missing \
    aptitude \
    zlib1g-dev \
    libzip-dev \
    wget \
    nano \
    vim \
    curl \
    unzip \
    git \
    software-properties-common

# install zip extension
RUN docker-php-ext-install zip pdo pdo_mysql

# install nodejs and yarn
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt-get update -y && apt-get install -y --fix-missing \
    nodejs
RUN npm install -g yarn