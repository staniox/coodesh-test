FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libssl-dev \
    pkg-config \
    libzip-dev \
    unzip \
    zlib1g-dev \
    procps \
    && docker-php-ext-install mbstring exif pcntl bcmath gd

RUN echo "memory_limit = 500M" > /usr/local/etc/php/conf.d/docker-fpm.ini

RUN if ! php -m | grep -q 'mongodb'; then pecl install mongodb && docker-php-ext-enable mongodb; fi

RUN docker-php-ext-install zip

WORKDIR /var/www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer


COPY . .

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

RUN composer install --optimize-autoloader
RUN RUN php artisan key:generate

EXPOSE 9000

CMD ["php-fpm"]
