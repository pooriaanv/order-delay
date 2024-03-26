FROM php:8.2-fpm


# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nano \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev libxpm-dev \
    libfreetype6-dev


RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg

RUN docker-php-ext-install zip pdo pdo_pgsql pgsql gd bcmath

RUN pecl install timezonedb docker-php-ext-enable timezonedb

RUN curl -sS https://getcomposer.org/installer | php -- \
            --install-dir=/usr/local/bin --filename=composer --verison 2.0.7

ENV PATH="~/.composer/vendor/bin:./vendor/bin:${PATH}"

COPY . /var/www/html

WORKDIR /var/www/html/

COPY composer.* /var/www/html/

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

COPY ./docker/entrypoint.sh /entrypoint.sh

ENTRYPOINT sh "/entrypoint.sh"
