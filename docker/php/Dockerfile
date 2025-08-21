FROM php:8.3-fpm

WORKDIR /var/www/html

COPY api/ ./

RUN docker-php-ext-install pdo_mysql

EXPOSE 9000
CMD ["php-fpm", "-F"]
