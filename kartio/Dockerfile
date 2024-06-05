FROM php:8-fpm-alpine

WORKDIR /var/www

RUN apk --update add --virtual build-dependencies build-base openssl-dev autoconf \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb \
  && apk del build-dependencies build-base openssl-dev autoconf \
  && rm -rf /var/cache/apk/*

RUN apk --no-cache add \
    nodejs \
    npm

# Register the COMPOSER_HOME environment variable
ENV COMPOSER_HOME /composer

# Add global binary directory to PATH and make sure to re-export it
ENV PATH /composer/vendor/bin:$PATH

# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --quiet

COPY . .

RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www

USER www-data

RUN npm i -D daisyui@latest

# Install PHP dependencies
RUN composer install --no-interaction --no-progress --no-dev && \
    composer dump-env prod

# Start PHP-FPM
CMD ["php-fpm"]
