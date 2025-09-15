FROM php:8.2-fpm

# system deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libonig-dev libxml2-dev \
    zip curl libpng-dev libjpeg-dev libfreetype6-dev \
    python3 make g++ \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip bcmath gd

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# node 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html

COPY docker/app/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# expose phpfpm
EXPOSE 9000

CMD ["/usr/local/bin/start.sh"]
