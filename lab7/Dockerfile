FROM php:8.2-fpm

RUN apt-get update && apt-get install -y git unzip curl

WORKDIR /var/www/html

COPY ./www /var/www/html

# Просто создаем папку для данных
RUN mkdir -p /var/www/html/data

CMD ["php-fpm"]
