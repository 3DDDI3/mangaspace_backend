FROM php:8.3-fpm-alpine

# ARG uid=1000
# ARG gid=1000

# RUN addgroup -g $uid --system laravel
# RUN adduser -G laravel --system -D -s /bin/sh -u $gid laravel
# RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
# RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf


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

# RUN chmod -R 777 /var/www/laravel

# USER laravel