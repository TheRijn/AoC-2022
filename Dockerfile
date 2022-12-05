FROM php:8.1-cli-alpine

RUN apk add --update --no-cache --virtual build-dependencies build-base autoconf && \
    pecl install -o ds && \
    echo "extension=ds.so" > /usr/local/etc/php/conf.d/ds.ini && \
    apk del build-dependencies

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
