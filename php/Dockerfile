FROM php:8.0.0RC5-fpm-alpine AS base

RUN \
  # zip/unzip: easing composer deps fetching
  apk add --no-cache zip unzip libzip-dev  && docker-php-ext-install zip && \
  # database: PostgreSQL
  apk add --no-cache postgresql-dev && docker-php-ext-install pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer 

WORKDIR /var/app

##############################################################################################################
FROM base AS dev

RUN curl -sL https://github.com/xdebug/xdebug/archive/3.0.0RC1.tar.gz | tar -xz && \
  mkdir -p /usr/src/php/ext/ && \
  mv xdebug-3.0.0RC1 /usr/src/php/ext/xdebug && \
  docker-php-ext-install xdebug && \
  echo "memory_limit = -1" >> /usr/local/etc/php/php.ini;

ARG XDEBUG_CLIENT_HOST
RUN echo "xdebug.client_host=${XDEBUG_CLIENT_HOST}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
  echo "xdebug.log_level=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ENV APP_ENV=dev

##############################################################################################################
FROM base as test

RUN curl -sL https://github.com/xdebug/xdebug/archive/3.0.0RC1.tar.gz | tar -xz && \
  mkdir -p /usr/src/php/ext/ && \
  mv xdebug-3.0.0RC1 /usr/src/php/ext/xdebug && \
  docker-php-ext-install xdebug && \
  echo "memory_limit = -1" >> /usr/local/etc/php/php.ini;

ENV APP_ENV=test

# PHP dependencies fetching (docker layer optimisation)
COPY composer.* /var/app/
RUN composer install --prefer-dist --no-scripts --optimize-autoloader && rm -rf /root/.composer

# PHP app bootstrap
COPY . /var/app

##############################################################################################################
FROM base AS prod

ENV APP_ENV=prod

COPY . /var/app
RUN composer install --prefer-dist --no-dev --classmap-authoritative && rm -rf /root/.composer

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
  sed -i 's/memory_limit = .*/memory_limit = 512M/' /usr/local/etc/php/php.ini
