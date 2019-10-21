FROM composer:1.6.5 as build
WORKDIR /app
COPY . /app
RUN composer install --quiet --optimize-autoloader --no-dev

FROM nanoninja/php-fpm:7.3.6
WORKDIR /var/www/html
RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y \
    nginx \
    && apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y \
    && rm -f  /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm -f  /etc/nginx/sites-enabled/default \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*

EXPOSE 80
COPY --from=build /app /var/www/html
COPY etc/nginx/default.production.conf /etc/nginx/conf.d/default.conf
COPY etc/php/php.production.ini /usr/local/etc/php/conf.d/php.ini
RUN mkdir -p storage/logs \
    && mkdir -p storage/app/cache \
    && mkdir -p storage/app/compiled \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/testing \
    && chown -R www-data:www-data storage \
    && php artisan config:cache \
    && php artisan route:cache

CMD service nginx start && php-fpm
