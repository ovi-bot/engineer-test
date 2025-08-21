FROM composer:2

WORKDIR /app

COPY api/ ./

RUN composer install --no-interaction --prefer-dist --no-progress

CMD ["vendor/bin/phpunit", "--colors=always"]
