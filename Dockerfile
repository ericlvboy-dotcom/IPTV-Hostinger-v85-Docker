FROM php:8.2-apache
RUN apt-get update \
 && apt-get install -y --no-install-recommends unzip libcurl4-openssl-dev libxml2-dev libonig-dev \
 && docker-php-ext-install -j2 curl dom mbstring \
 && a2enmod rewrite headers \
 && rm -rf /var/lib/apt/lists/*
COPY website/IPTV-v85-DietPi-Docker-Ready.zip /tmp/original-site.zip
RUN mkdir -p /tmp/original-unpacked \
 && unzip -q /tmp/original-site.zip -d /tmp/original-unpacked \
 && test -f /tmp/original-unpacked/IPTV-v85-DietPi-Docker/website/index.php \
 && cp -a /tmp/original-unpacked/IPTV-v85-DietPi-Docker/website/. /var/www/html/ \
 && mkdir -p /var/www/html/storage \
 && chown -R www-data:www-data /var/www/html/storage \
 && rm -rf /tmp/original-unpacked /tmp/original-site.zip
CMD ["apache2-foreground"]
