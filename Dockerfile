FROM php:8.3-alpine

#WORKDIR /app

COPY --from=composer:2.6.6 /usr/bin/composer /usr/local/bin/composer

RUN apk -U add bash \
    && adduser -D dev \
    && rm -rf /tmp/*

USER dev
