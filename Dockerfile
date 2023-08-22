# Dockerfile
FROM php:8.2-apache AS base

# ENABLE REWRITING
RUN a2enmod rewrite

# Update package list and install packages
RUN apt-get update && apt-get install -y \
    wget \
    git \
    libzip-dev \
    unzip \
    libicu-dev \
    zlib1g-dev \
    && docker-php-ext-install zip


# COPY COMPOSER 2.<LATEST> AND INSTALL
RUN wget https://getcomposer.org/composer-2.phar
RUN mv composer-2.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

# SET USER PERMISSIONS FOR COPIED APP.
RUN chown -R www-data:www-data /var/www/html

# SET USER PERMISSIONS FOR MOUNTED VOLUMES.
RUN usermod -u 1000 www-data