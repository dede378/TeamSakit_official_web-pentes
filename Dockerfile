FROM php:8.4-apache

RUN docker-php-ext-install pdo pdo_mysql \
 && a2enmod rewrite headers
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY app/ /var/www/app/
COPY public/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html /var/www/app
EXPOSE 80
