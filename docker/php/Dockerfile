FROM php:8.2.2-fpm-alpine3.17

RUN apk update
RUN apk add --update linux-headers

RUN apk add --no-cache \
    curl \
    bash \
    nano \
    git \
    libzip-dev \
    zip \
    $PHPIZE_DEPS


RUN apk add --no-cache \
    openssl \
    libssl3 \
    openssl-dev


RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin \
    --filename=composer

RUN pecl update-channels && \
    pecl install xdebug && \
    pecl install xhprof && \
    pecl install redis && \
    pecl install mongodb

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    opcache \
    zip \
    exif

RUN docker-php-ext-enable \
    xdebug \
    xhprof \
    redis.so \
    mongodb

RUN apk add --update nodejs npm

RUN apk add libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd

ARG UNAME
ARG UID
ARG GID

ENV USR=$UNAME
ENV GRP=$UNAME

RUN addgroup \
    -g "$GID" \
    -S "$GRP" && \
	adduser \
	--disabled-password \
    -g "$GID" \
	-D \
	-s "/bin/bash" \
	-u "$UID" \
	-G "$GRP" "$USR" && \
    echo "$USR ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

RUN chown -R $USR:$GRP "/var/www"

USER $USR:$GRP

