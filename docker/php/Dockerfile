ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm-alpine AS development

ARG APCU_VERSION
ARG COMPOSER_VERSION=2.5.1

RUN addgroup -g 1000 -S app && \
    adduser -u 1000 -S app -G app

RUN apk add \
    autoconf \
    build-base \
    git

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu-${APCU_VERSION}
RUN docker-php-ext-enable apcu opcache

COPY --from=composer:2.5.1 /usr/bin/composer /usr/local/bin/composer

WORKDIR /srv/app

RUN apk add \
    zsh \
    ncurses \
  ;

USER app

RUN sh -c "$(wget -O- https://github.com/deluan/zsh-in-docker/releases/download/v1.1.1/zsh-in-docker.sh)" -- \
    -t robbyrussell \
    -p https://github.com/zsh-users/zsh-autosuggestions \
    -p https://github.com/zsh-users/zsh-syntax-highlighting

USER root
