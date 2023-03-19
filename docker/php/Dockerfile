ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm-alpine AS base

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

FROM base AS production

COPY api/composer.* api/symfony.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN set -eux; \
	composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress \
	&& composer clear-cache \
    && composer dumpautoload

COPY .env.prod .env
COPY api/bin bin/
COPY api/config config/
COPY api/public public/
COPY api/src src/
COPY api/migrations migrations/
COPY api/templates templates/

FROM base AS deveopment

RUN apk add \
    zsh \
    ncurses \
    yamllint \
  ;

USER app

RUN sh -c "$(wget -O- https://github.com/deluan/zsh-in-docker/releases/download/v1.1.1/zsh-in-docker.sh)" -- \
    -t robbyrussell \
    -p https://github.com/zsh-users/zsh-autosuggestions \
    -p https://github.com/zsh-users/zsh-syntax-highlighting

USER root
