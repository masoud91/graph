ARG PHP_VERSION=7.3

FROM php:${PHP_VERSION}-fpm-alpine

MAINTAINER Masoud Aghaei

# persistent / runtime deps
RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
	;

ARG APCU_VERSION=5.1.18
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		mysqli \
		pdo \
		pdo_mysql \
		zip \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
	; \
	\
	apk del .build-deps

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
      && pecl install redis \
      && docker-php-ext-enable redis \
      && apk del pcre-dev ${PHPIZE_DEPS} \
      && rm -rf /tmp/pear

RUN rm "$PHP_INI_DIR/conf.d/docker-php-ext-intl.ini" "$PHP_INI_DIR/conf.d/docker-php-ext-zip.ini"

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Setup project directory
RUN mkdir /app
WORKDIR /app
COPY ./app /app

# run as current user
RUN adduser -D user
USER user

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]