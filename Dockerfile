FROM composer:1.6.5 as build
WORKDIR /app
COPY . /app
RUN composer global require hirak/prestissimo \
    && composer install --no-interaction --optimize-autoloader --no-dev

FROM node:10 as node_build
WORKDIR /app
COPY package.json /app
COPY yarn.lock /app
RUN yarn install --prod

FROM nanoninja/php-fpm:7.3.6
WORKDIR /var/www/html
EXPOSE 80
EXPOSE 3000

RUN apt-get -qq update  &> /dev/null \
    && apt-get -qq upgrade -y &> /dev/null \
    && apt-get -qq install -y \
    nginx \
    curl \
    gnupg \
    && curl -sL https://deb.nodesource.com/setup_10.x | bash &> /dev/null \
    && apt-get -qq install -y \
    nodejs \
    && apt-get -qq remove -y \
    curl \
    gnupg &> /dev/null \
    && apt-get -qq autoremove --purge -y &> /dev/null \
    && apt-get -qq autoclean -y  &> /dev/null \
    && apt-get -qq clean -y &> /dev/null \
    && rm -f  /etc/apt/sources.list.d/nodesource.list \
    && rm -f  /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm -f  /etc/nginx/sites-enabled/default \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*

COPY --from=build /app /var/www/html
COPY --from=build /app/vendor /var/www/html/vendor
COPY --from=node_build /app/node_modules /var/www/html/node_modules

COPY etc/nginx/default.production.conf /etc/nginx/conf.d/default.conf
COPY etc/php/php.production.ini /usr/local/etc/php/conf.d/php.ini
COPY resources/docker/start.sh /usr/local/bin/start

RUN mkdir -p storage/logs \
    && mkdir -p storage/app/cache \
    && mkdir -p storage/app/compiled \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/testing \
    && chown -R www-data:www-data storage \
    && chmod u+x /usr/local/bin/start

CMD ["/usr/local/bin/start"]
