FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    unzip zip git curl && \
    docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install || true

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80