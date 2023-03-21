FROM php:8.1-fpm-buster
ARG TIMEZONE

ARG BUILD_ARGUMENT_ENV=dev
ENV ENV=$BUILD_ARGUMENT_ENV
ENV APP_HOME /var/www/html
ARG HOST_UID=1000
ARG HOST_GID=1000
ENV USERNAME=www-data
ARG INSIDE_DOCKER_CONTAINER=1
ENV INSIDE_DOCKER_CONTAINER=$INSIDE_DOCKER_CONTAINER
ARG XDEBUG_CONFIG=main
ENV XDEBUG_CONFIG=$XDEBUG_CONFIG

RUN if [ "$BUILD_ARGUMENT_ENV" = "default" ]; then echo "Set BUILD_ARGUMENT_ENV in docker build-args like --build-arg BUILD_ARGUMENT_ENV=dev" && exit 2; \
    elif [ "$BUILD_ARGUMENT_ENV" = "dev" ]; then echo "Building development environment."; \
    elif [ "$BUILD_ARGUMENT_ENV" = "test" ]; then echo "Building test environment."; \
    elif [ "$BUILD_ARGUMENT_ENV" = "staging" ]; then echo "Building staging environment."; \
    elif [ "$BUILD_ARGUMENT_ENV" = "prod" ]; then echo "Building production environment."; \
    else echo "Set correct BUILD_ARGUMENT_ENV in docker build-args like --build-arg BUILD_ARGUMENT_ENV=dev. Available choices are dev,test,staging,prod." && exit 2; \
    fi

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini


RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    gnupg \
    g++ \
    procps \
    nano \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libxml2 \
    libxml2-dev \
    libfreetype6-dev \
    librabbitmq-dev \
    libpng-dev \
    libreadline-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    supervisor \
    cron \
    sudo \
    wget \
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

RUN pecl install xdebug-3.2.0
#RUN docker-php-ext-enable xdebug

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u ${HOST_GID} ${USERNAME} && groupmod -g ${HOST_GID} ${USERNAME}

WORKDIR /var/www

EXPOSE 9000
