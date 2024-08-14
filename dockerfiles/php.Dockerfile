FROM php:8.3-fpm-alpine

RUN apk add --no-cache linux-headers \
  libtool \
  autoconf \
  gcc \
  g++ \
  make

RUN docker-php-ext-install \
  pdo \
  pdo_mysql \
  sockets 

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
  pcntl

WORKDIR /var/www/laravel