FROM php:8.3-fpm-alpine AS base

#WORKDIR /app

COPY --from=composer:2.6.6 /usr/bin/composer /usr/local/bin/composer

RUN apk -U add \
      bash \
    && adduser -D dev \
    && rm -rf /tmp/*

RUN docker-php-ext-install pdo_mysql

USER dev

FROM base AS dev
USER root
RUN apk -U add less vim
RUN apk -U add php83-dev g++ make linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug
RUN apk -U add icu-dev \
    && docker-php-ext-install intl
USER dev
