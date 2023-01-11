FROM php:7.4-fpm-buster
ARG TIMEZONE

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini

RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    librabbitmq-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    libssh-dev \
    libpq-dev \
    postgresql \
    acl \
    make \
    gcc \
    protobuf-compiler \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-install \
    pdo mysqli pdo_mysql zip xsl gd intl opcache exif mbstring bcmath sockets

RUN pecl install -o -f ast && docker-php-ext-enable ast
RUN pecl install -o -f protobuf
RUN pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis
RUN pecl install -o -f amqp && docker-php-ext-enable amqp

RUN pecl install xdebug-2.9.0 \
    && docker-php-ext-enable xdebug

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www

EXPOSE 9000
