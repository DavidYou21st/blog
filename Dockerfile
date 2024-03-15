FROM php:8.1-fpm

#更换源
#RUN sed -i "s/deb.debian.org/mirrors.aliyun.com/g" /etc/apt/sources.list

WORKDIR /var/www

# Update packages

RUN apt-get update \
    && apt-get install -y apt-transport-https ca-certificates g++ netcat-traditional sudo curl zip unzip libonig5 libonig-dev libwebp-dev libxpm-dev libfreetype-dev libicu-dev libmcrypt-dev libjpeg-dev libpng-dev  libbz2-dev git \
    && apt-get clean

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli exif pcntl bz2 calendar mbstring
RUN docker-php-ext-configure gd --with-webp --with-jpeg --with-freetype
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .
COPY .env.example .env

CMD ["bash", "./blog-entrypoint.sh"]

