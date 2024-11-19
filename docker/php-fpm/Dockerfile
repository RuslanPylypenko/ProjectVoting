FROM php:8.3.0-fpm-alpine

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev libzip-dev autoconf bash linux-headers libpq-dev && \
    echo "memory_limit=128M" >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini && \
    echo 'max_execution_time = 60' >> /usr/local/etc/php/conf.d/docker-php-maxexectime.ini;


RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS\
    && docker-php-ext-install pdo_mysql zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug opcache \
    && pecl clear-cache \
    && apk del .build-dependencies

COPY ./docker/php-fpm/config/xdebug.ini /usr/local/etc/php/conf.d/

# Copy php.ini
COPY ./docker/common/php.ini /usr/local/etc/php/conf.d/php.ini

# Copy opcache.ini
COPY ./docker/common/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Set env params for opcache
ENV PHP_OPCACHE_REVALIDATE_FREQ=${PHP_OPCACHE_REVALIDATE_FREQ:-60}
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=${PHP_OPCACHE_VALIDATE_TIMESTAMPS:-0}

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000

RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Source code
RUN chown www-data:www-data /var/www
COPY --chown=www-data:www-data ./ /var/www
WORKDIR /var/www/api

USER www-data

CMD ["sh", "-c", "docker-php-entrypoint php-fpm"]

EXPOSE 9000