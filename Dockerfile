FROM php:8.2-apache
RUN apt-get update && apt-get install -y --no-install-recommends libcurl4-openssl-dev libxml2-dev libonig-dev && docker-php-ext-install -j2 curl dom mbstring && a2enmod rewrite headers && rm -rf /var/lib/apt/lists/*
COPY website/ /var/www/html/
RUN mkdir -p /var/www/html/storage && chown -R www-data:www-data /var/www/html/storage
