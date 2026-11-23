FROM php:8.2-apache
RUN apt-get update \
 && apt-get install -y --no-install-recommends unzip libcurl4-openssl-dev libxml2-dev libonig-dev \
 && docker-php-ext-install -j2 curl dom mbstring \
 && a2enmod rewrite headers \
 && rm -rf /var/lib/apt/lists/*
COPY website/IPTV-v85-DietPi-Docker-Ready.zip /tmp/original-site.zip
RUN mkdir -p /opt/original-site \
 && unzip -q /tmp/original-site.zip -d /tmp/original-unpacked \
 && test -f /tmp/original-unpacked/IPTV-v85-DietPi-Docker/website/index.php \
 && cp -a /tmp/original-unpacked/IPTV-v85-DietPi-Docker/website/. /opt/original-site/ \
 && rm -rf /tmp/original-unpacked /tmp/original-site.zip
RUN printf '%s\n' '#!/bin/sh' 'set -eu' 'mkdir -p /var/www/html/storage' 'find /var/www/html -mindepth 1 -maxdepth 1 ! -name storage -exec rm -rf -- {} +' 'cp -a /opt/original-site/. /var/www/html/' 'chown -R www-data:www-data /var/www/html/storage' 'exec apache2-foreground' > /usr/local/bin/start-original-site \
 && chmod +x /usr/local/bin/start-original-site
CMD ["/usr/local/bin/start-original-site"]
