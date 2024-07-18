# FROM php:7.4-apache
FROM pensiero/apache-php:php7.4

WORKDIR /var/www/html
COPY . /var/www/html

# PHP
RUN apt-get update
RUN apt-get install -y zlib1g-dev libwebp-dev libpng-dev && docker-php-ext-install gd
RUN apt-get install libzip-dev -y && docker-php-ext-install zip
# RUN apt-get install php-mysqli
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql



# Apache
RUN service apache2 restart

EXPOSE 80