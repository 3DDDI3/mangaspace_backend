FROM php:8.3-fpm-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
    pcntl

WORKDIR /var/www/laravel

# RUN composer require laravel/reverb:@beta --ignore-platform-req=ext-sockets

# CMD php artisan reverb:install

RUN apk update && apk add --no-cache supervisor \
  nodejs \
  npm

RUN touch /var/run/supervisor.sock

RUN mkdir -p "/etc/supervisor/logs"

EXPOSE 8000

WORKDIR /usr/bin