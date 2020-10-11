FROM php:7.4.7-fpm

ARG XDEBUG_VERSION=xdebug-2.9.5

ARG user
ARG uid

RUN apt-get update && apt-get install -y libxslt-dev rsync libicu-dev libjpeg62-turbo-dev libpng-dev libzip-dev zlib1g-dev zip unzip git curl locales nano && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get update && apt-get install nodejs -y
RUN apt-get update && apt-get install -f

RUN docker-php-ext-install pdo_mysql zip exif bcmath intl opcache xsl

RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install gd
    
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN pecl install ${XDEBUG_VERSION} && docker-php-ext-enable xdebug
RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.idekey="PHPSTORM"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.profiler_enable=0' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.profiler_enable_trigger=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.profiler_output_dir="/var/www/data/profiler"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.profiler_enable_trigger="cachegrind.out.%p"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN mkdir -p /opt/phpstorm-coverage
RUN chmod 777 /opt/phpstorm-coverage

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    mkdir -p /home/$user/.ssh && \
    chown -R $user:$user /home/$user

RUN touch /home/$user/.ssh/authorized_keys

USER $user