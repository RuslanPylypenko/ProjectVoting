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

COPY ./config/xdebug.ini /usr/local/etc/php/conf.d/

# Copy php.ini
COPY ./config/php.ini /usr/local/etc/php/conf.d/php.ini

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000

RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Install Composer globally
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Source code
RUN chown www-data:www-data /var/www
COPY --chown=www-data:www-data ./ /var/www
WORKDIR /var/www/api

USER www-data

CMD ["sh", "-c", "docker-php-entrypoint php-fpm"]

EXPOSE 9000