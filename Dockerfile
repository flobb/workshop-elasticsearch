FROM jwilder/dockerize:0.6.0 AS dockerize
FROM composer:1.6 AS composer
FROM php:7.2-fpm-alpine3.7

# CircleCI required tools
# https://circleci.com/docs/2.0/custom-images/#adding-required-and-custom-tools-or-files
RUN apk add --no-cache make git openssh tar gzip ca-certificates

ENV APCU_VERSION 5.1.9
RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        zlib-dev \
    && docker-php-ext-install \
        intl \
        zip \
        pdo \
        pdo_mysql \
    && pecl install \
        apcu-${APCU_VERSION} \
    && docker-php-ext-enable --ini-name 20-apcu.ini apcu \
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache \
    && runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )" \
    && apk add --no-cache --virtual .php-phpexts-rundeps $runDeps \
    && apk del .build-deps

RUN apk add --no-cache gcc g++ autoconf

# Meminfo
RUN curl -L -o /tmp/meminfo.tar.gz https://github.com/BitOne/php-meminfo/archive/master.tar.gz \
    && tar zxpf /tmp/meminfo.tar.gz -C /tmp \
    && rm -r /tmp/meminfo.tar.gz \
    && cd /tmp/php-meminfo-master/extension/php7/ && phpize && ./configure --enable-meminfo && make && make install \
    && printf "extension=meminfo.so\n" > $PHP_INI_DIR/conf.d/meminfo.ini

# Blackfire probe
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/alpine/amd64/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

COPY --from=dockerize /usr/local/bin/dockerize /usr/bin/dockerize
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY .docker/php.ini /usr/local/etc/php/php.ini

RUN chmod 777 /tmp
RUN chmod +x /usr/local/bin/docker-entrypoint

WORKDIR /srv
ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
