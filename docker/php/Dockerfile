FROM php:7

WORKDIR /var/www

COPY config/php.ini-development /usr/local/etc/php/

RUN apt-get update && apt-get install -y \
    vim \
    git \
    curl \
    zlib1g-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng12-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql bcmath mbstring opcache sockets

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# change php timezone
RUN echo "date.timezone = America/Sao_Paulo" >> /usr/local/etc/php/php.ini

# add composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# execute composer without Xdebug
RUN echo "alias composer='php -n -dextension=zip.so -dextension=bcmath.so -dextension=gd.so /usr/local/bin/composer'" >> ~/.bashrc \
    && echo "export TERM=xterm" >> ~/.bashrc

# Xdebug config
RUN echo -e "\
xdebug.var_display_max_depth = 10\n \
xdebug.var_display_max_children = 256\n \
xdebug.var_display_max_data = 1024\n \

xdebug.default_enable=1\n \
xdebug.remote_enable=1\n \
xdebug.remote_host=172.17.0.1\n \
xdebug.remote_port=9000\n \
xdebug.idekey=PHPSTORM\n \

xdebug.profiler_output_dir=/var/www/\n \
xdebug.profiler_enable_trigger=1\
" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

EXPOSE 80
