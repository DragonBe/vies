FROM php:7.1-cli

RUN apt-get update \
  && apt-get install -y \
    curl \
    git \
    libzip-dev \
    libxml2-dev \
    unzip \
    zlib1g-dev

RUN curl -sSLo xdebug-latest.tar.gz https://xdebug.org/files/xdebug-2.7.0beta1.tgz \
  && mkdir -p /tmp/xdebug \
  && tar --strip-components=1 -C /tmp/xdebug -xf xdebug-latest.tar.gz \
  && rm xdebug-latest.tar.gz \
  && ( \
    cd /tmp/xdebug \
    && phpize \
    && ./configure --with-php-config=/usr/local/bin/php-config --enable-xdebug \
    && make -j "$(nproc)" \
    && make install \
  ) \
  && rm -rf /tmp/xdebug \
  && docker-php-ext-enable xdebug

RUN docker-php-ext-install -j$(nproc) \
    soap \
    pcntl \
    zip 

RUN useradd -m -s /bin/bash phpuser \
  && mkdir -p /usr/src/php \
  && chown -R phpuser:phpuser /usr/src/php \
  && chown -R phpuser:phpuser /home/phpuser \
  && chmod -R ug+w /usr/src/php

USER phpuser

WORKDIR /usr/src/php
VOLUME /usr/src/php

RUN curl -sSL https://getcomposer.org/installer | php
