FROM php:8.2-apache
RUN apt-get update \
 && apt-get install -y --no-install-recommends unzip libcurl4-openssl-dev libxml2-dev libonig-dev \
 && docker-php-ext-install -j2 curl dom mbstring \
 && a2enmod rewrite headers \
 && rm -rf /var/lib/apt/lists/*
COPY website/IPTV-v85-DietPi-Docker-Ready.zip /tmp/iptv-v85.zip
RUN mkdir -p /tmp/iptv-unpacked /var/www/html \
 && unzip -q /tmp/iptv-v85.zip -d /tmp/iptv-unpacked \
 && test -f /tmp/iptv-unpacked/IPTV-v85-DietPi-Docker/website/index.php \
 && cp -a /tmp/iptv-unpacked/IPTV-v85-DietPi-Docker/website/. /var/www/html/ \
 && mkdir -p /var/www/html/storage \
 && chown -R www-data:www-data /var/www/html/storage \
 && rm -rf /tmp/iptv-unpacked /tmp/iptv-v85.zip
