FROM php:latest
MAINTAINER Alihuseyn Gulmammadov <alihuseyn13@gmail.com>
LABEL description="PHP & Composer Image"
LABEL version="1.0.0"
RUN apt-get update \
    && apt-get install -y \
        libpng12-dev \
        libjpeg-dev  \
        curl \
        sed \
        zlib1g-dev \
    && docker-php-ext-install \
        zip \
        mysqli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /app