FROM php:8.0.0RC5-fpm-alpine AS base

RUN apk update && \
  # zip/unzip: easing composer deps fetching
  apk add  zip unzip libzip-dev  && docker-php-ext-install zip && \
  # database: PostgreSQL
  apk add postgresql-dev && docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer 

WORKDIR /var/app

##############################################################################################################
FROM base AS dev

RUN curl -sL https://github.com/xdebug/xdebug/archive/3.0.0RC1.tar.gz | tar -xz && \
  mkdir -p /usr/src/php/ext/ && \
  mv xdebug-3.0.0RC1 /usr/src/php/ext/xdebug && \
  docker-php-ext-install xdebug 

ENV APP_ENV=dev

RUN echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini 

RUN echo "memory_limit = -1" >> /usr/local/etc/php/php.ini;

##############################################################################################################
FROM base as test

RUN curl -sL https://github.com/xdebug/xdebug/archive/3.0.0RC1.tar.gz | tar -xz && \
  mkdir -p /usr/src/php/ext/ && \
  mv xdebug-3.0.0RC1 /usr/src/php/ext/xdebug && \
  docker-php-ext-install xdebug 

ENV APP_ENV=test

RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# PHP dependencies fetching (docker layer optimisation)
COPY composer.* /var/app/
RUN composer install --prefer-dist --no-scripts --optimize-autoloader && rm -rf /root/.composer

# PHP app bootstrap
COPY . /var/app
RUN php bin/console cache:clear

RUN echo "memory_limit = 128M" >> /usr/local/etc/php/php.ini;

##############################################################################################################
FROM base AS prod

ENV APP_ENV=prod

COPY . /var/app
RUN composer install --prefer-dist --no-dev --classmap-authoritative && rm -rf /root/.composer

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
  sed -i 's/memory_limit = .*/memory_limit = 512M/' /usr/local/etc/php/php.ini
