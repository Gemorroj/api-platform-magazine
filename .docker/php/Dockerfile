FROM php:7.4-fpm-alpine

RUN set -xe \
    && apk update \
    && apk upgrade \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add --no-cache icu-dev \
    && apk add --no-cache postgresql-dev \
    && apk add --no-cache libsodium-dev \

    && pecl install apcu \
    && docker-php-ext-enable apcu \

    && pecl install xdebug \
    && docker-php-ext-enable xdebug \

    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql \

    && docker-php-ext-install sodium \
    && docker-php-ext-enable sodium \

    && docker-php-ext-install intl \
    && docker-php-ext-enable intl \

    && curl -L -o /composer.phar https://github.com/composer/composer/releases/download/1.10.5/composer.phar \
    && chmod 777 /composer.phar \

    && apk del .build-deps \
    && rm -rf /tmp/* /var/cache/apk/* \

    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Override with custom opcache settings
COPY opcache.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/www/app
