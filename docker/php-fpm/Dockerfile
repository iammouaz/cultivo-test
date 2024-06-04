FROM phpdockerio/php:8.2-fpm

# Copy composer.json
COPY ./core /var/www/mcultivo-auction
COPY ./core/composer.json /var/www/mcultivo-auction/composer.json

# Set working directory
WORKDIR /var/www/mcultivo-auction

RUN apt-get update && \
    apt-get -y --no-install-recommends install \
        php8.2-bcmath \
        php8.2-gd \
        php8.2-imagick \
        php8.2-mcrypt \
        php8.2-mysql \
        php8.2-redis \
        php8.2-uuid \
        php8.2-xmlrpc \
        php8.2-yaml; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN chown -R www-data:www-data /var/www/mcultivo-auction

RUN chmod -R 775 /var/www/mcultivo-auction

# Change current user to www
WORKDIR /var/www/mcultivo-auction

RUN composer update

RUN php artisan config:cache

RUN php artisan key:generate

CMD php artisan serve --host=0.0.0.0
