FROM php:7.3-cli-alpine
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apk add --no-cache autoconf coreutils build-base php7 \
      && rm -rf /var/cache/apk/* \
      && pecl install xdebug-beta  \
      && docker-php-ext-enable xdebug
WORKDIR /app
