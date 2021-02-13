# alpine3.13 is broken
FROM php:7.4-fpm-alpine3.12

RUN set -xe \
    && apk update \
    && apk upgrade \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \

    # Sodium
    && apk add --no-cache libsodium-dev \
    && docker-php-ext-install sodium \
    && docker-php-ext-enable sodium \

    # INTL
    && apk add --no-cache icu-dev icu-libs \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl \

    # Xdebug
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \

    # Postgresql
    && apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql \

    # APCU
    && pecl install apcu \
    && docker-php-ext-enable apcu \

    # Blackfire https://blackfire.io/docs/integrations/docker/index
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/alpine/amd64/$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get ('extension_dir');")/blackfire.so \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz \

    # Cleanup
    && apk del --no-cache .build-deps \
    && apk del --no-cache icu-dev \
    && apk del --no-cache libsodium-dev \
    && rm -rf /tmp/* /var/cache/apk/*


# Composer
RUN set -xe \
    && curl -L -o /composer.phar https://github.com/composer/composer/releases/download/1.10.20/composer.phar \
    && chmod 755 /composer.phar


# utilites
RUN set -xe \
    && apk --no-cache add htop unzip


WORKDIR /var/www/app
