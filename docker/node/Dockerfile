ARG NODE_VERSION

FROM node:${NODE_VERSION}-alpine AS development

WORKDIR /srv/app

USER 1000

FROM development AS builder

USER root

COPY app/package.json app/package-lock.json ./

RUN yarn install

COPY app/. .

RUN yarn build

USER 1000

FROM development AS production

COPY --from=builder /srv/app/dist /srv/app/
