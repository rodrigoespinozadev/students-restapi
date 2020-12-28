FROM php:7.4-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
RUN apt update \
        && apt install -y --no-install-recommends \
        && docker-php-ext-install pdo_mysql \
        && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]