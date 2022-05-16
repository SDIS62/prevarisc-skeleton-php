FROM php:8.0-apache-bullseye

# PHP Extensions
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install sockets
RUN docker-php-ext-install bcmath

# Zip (Composer downloader)
RUN apt-get update && apt-get install -y --no-install-recommends libzip-dev && apt-get clean && rm -rf /var/lib/apt/lists/* && docker-php-ext-install zip

# Apache2 configuration
COPY config/apache2.conf /etc/apache2/sites-available/000-default.conf 
RUN a2enmod rewrite
RUN (echo "ServerName localhost" | tee /etc/apache2/conf-available/servername.conf) && a2enconf servername

USER www-data

# Base dir
WORKDIR /var/www/html

# Install dependencies (prod mode)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install -n --no-dev --no-progress --no-scripts --ignore-platform-reqs 

# Copy sources files
COPY public public
COPY config config
COPY src src
COPY tests tests