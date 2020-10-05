FROM php:7.4-apache

# Install MySQL extension
RUN docker-php-ext-install pdo_mysql

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Xdebug extension
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Replace Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable required apache modules
RUN a2enmod rewrite headers

# Fix filesystem permissions
ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
