FROM php:7.2-fpm

MAINTAINER Dmitriy Konstantinov <dmitriy@konstantinov.com.ru>

ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update

# PHP
# intl
RUN apt-get install -y libicu-dev \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install intl

# xml
RUN apt-get install -y \
	libxml2-dev \
	libxslt-dev \
	&& docker-php-ext-install \
		dom \
		xmlrpc \
		xsl

# images
RUN apt-get install -y \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
	libgd-dev \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install \
		gd \
		exif

# database
RUN docker-php-ext-install \
	mysqli \
	pdo \
	pdo_mysql

# strings
RUN docker-php-ext-install \
	gettext \
	mbstring

# math
RUN apt-get install -y libgmp-dev \
	&& ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h \
	&& docker-php-ext-install \
		gmp \
		bcmath

# compression
RUN apt-get install -y \
	libbz2-dev \
	zlib1g-dev \
	&& docker-php-ext-install \
		zip \
		bz2

# ftp
RUN apt-get install -y \
	libssl-dev \
	&& docker-php-ext-install \
		ftp

# ssh2
RUN apt-get install -y \
	libssh2-1-dev

# memcached
RUN apt-get install -y \
	libmemcached-dev \
	libmemcached11

# others
RUN docker-php-ext-install \
	soap \
	sockets \
	calendar \
	sysvmsg \
	sysvsem \
	sysvshm

# yaml
RUN buildRequirements="libyaml-dev" \
	&& apt-get install -y ${buildRequirements} \
	&& pecl install yaml \
	&& echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini \
	&& apt-get purge -y ${buildRequirements}

# git
RUN apt-get install -y git

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# docker-compose
RUN apt-get install -y python python-pip python-requests
#RUN apt-get install -y docker-compose
RUN pip install docker-compose

# supervisor
RUN apt-get install -y supervisor
ADD yii2_queue.conf /etc/supervisor/conf.d/yii2_queue.conf

ADD docker-compose /etc/init.d/
RUN chmod 0755 /etc/init.d/docker-compose
# test
RUN apt-get install -y nano procps

# x-debug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" >> /usr/local/etc/php/php.ini
COPY ./xdebug.ini /usr/local/etc/php/conf.d/

RUN rm -rf /var/lib/apt/lists/*

WORKDIR /platform/core

EXPOSE 9000

#CMD /usr/bin/supervisord && /etc/init.d/docker-compose start && php-fpm
CMD /usr/bin/supervisord && php-fpm
