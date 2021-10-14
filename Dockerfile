FROM php:8.0-apache-buster

RUN apt-get update \
  && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libonig-dev \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv

COPY ./index.php /var/www/html/index.php
