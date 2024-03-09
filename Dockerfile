##############################
##        PHP  8.3          ##
##############################

ARG PHP_VERSION=8.3-fpm-alpine

FROM php:${PHP_VERSION}


# Bibliotecas do PHP
RUN apk --update add  zlib-dev \
    libzip-dev \
    libpng-dev \
    libpq \
    vim \
    fcgi \
    libxml2-dev \
    openssl \
    openssl-dev \
    iputils \
    libxslt-dev \
    libgcrypt-dev \
    libmcrypt-dev \
    gmp-dev \
    libpq-dev \
    libcurl \
    curl-dev \
    curl \
    acl \
    file \
    gettext \
    git \
    gnu-libiconv \
    gcompat \
    bind-tools\
    bash build-base gcc wget git autoconf libmcrypt-dev libzip-dev zip linux-headers

RUN docker-php-ext-install pdo pdo_mysql session xml bcmath opcache curl calendar

RUN docker-php-ext-install zip simplexml pcntl gd fileinfo

WORKDIR /var/www

COPY . .

EXPOSE 9004

#################################
##         LOGS PHP            ##
#################################

RUN mkdir -p /var/log/php
RUN chown www-data:adm /var/log/php
RUN chmod 755 /var/log/php

#################################
##           COMPOSER          ##
#################################

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#################################
##          XDEBUG             ##
#################################

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

EXPOSE 9003

#################################
##         NGINX 1.19.6        ##
#################################

RUN apk --update add nginx

#################################
##        SONAR SCANNER        ##
#################################

RUN apk add openjdk11 openjdk11-jre-headless
ENV JAVA_HOME=/usr/lib/jvm/java-11-openjdk

RUN apk update && \
    apk add --no-cache unzip

RUN curl -L -o /tmp/sonar-scanner-cli.zip https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.0.2966-linux.zip && \
    unzip /tmp/sonar-scanner-cli.zip -d /opt && \
    rm /tmp/sonar-scanner-cli.zip && \
    ln -s /opt/sonar-scanner-5.0.0.2966-linux /opt/sonar-scanner && \
    ln -s /opt/sonar-scanner/bin/sonar-scanner /usr/local/bin/sonar-scanner && \
    ln -s /opt/sonar-scanner/jre/lib/server/libjvm.so /opt/sonar-scanner/jre/lib

# Define a variável de ambiente PATH para incluir o diretório do SonarScanner
ENV PATH="${PATH}:/opt/sonar-scanner/bin"

RUN echo 'export PATH="/opt/sonar-scanner/bin:$PATH"' >> ~/.bashrc source ~/.bashrc

#################################
##          NODE.JS           ##
#################################

RUN apk update && \
    apk add --no-cache nodejs npm

#################################
##         SUPERVISOR          ##
#################################

### apt-utils é um extensão de recursos do gerenciador de pacotes APT
RUN apk --update add supervisor

COPY ./docker/supervisor/supervisord.conf /etc/supervisord.conf

CMD chmod -R 775 /var/www/ && chown -R www-data:www-data /var/www/ && /usr/bin/supervisord -c /etc/supervisord.conf