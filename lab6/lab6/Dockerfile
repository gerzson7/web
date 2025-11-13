FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY ./www /var/www/html

RUN composer install --no-dev --optimize-autoloader

# Копируем конфиг PHP-FPM
COPY ./www/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-custom.conf

CMD ["php-fpm", "-F"]
