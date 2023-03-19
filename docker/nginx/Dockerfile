ARG NGINX_VERSION

FROM nginx:${NGINX_VERSION}-alpine AS base

COPY ./docker/nginx/conf.d/default.prod.conf /etc/nginx/conf.d/default.conf

WORKDIR /srv/app

FROM base AS production

COPY ./api/public /srv/app/public
