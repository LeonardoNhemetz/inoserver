FROM php:7.4-fpm-alpine

RUN apk add --no-cache openssl bash mysql-client nodejs npm nano vim
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

WORKDIR /var/www

RUN rm -rf /var/www/html
RUN ln -s public html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 80

ENTRYPOINT [ "php-fpm" ]