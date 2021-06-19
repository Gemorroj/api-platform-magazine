FROM blackfire/blackfire:2

USER root

RUN set -xe \
    && apk update \
    && apk upgrade \
    && rm -rf /tmp/* /var/cache/apk/*

USER blackfire
