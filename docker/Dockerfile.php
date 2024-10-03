# Usa un'immagine base di PHP-FPM
FROM php:8.3-fpm

# Installa le estensioni di PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql zip intl

#COPY php.ini /usr/local/etc/php/

# Imposta la directory di lavoro
WORKDIR /var/www/html

# Installazione Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');" && \
    echo 'INSTALLING COMPOSER DEPENDENCIES...'
    #composer require monolog/monolog && \
    #composer require firebase/php-jwt

# www-data:www-data è il processo php-fpm che deve essere proprietario delle cartelle per scrivere i log
#chown -R www-data:www-data /var/www/html/api-v1
#chown www-data:www-data /var/www/html/app.log


# Espone la porta 9000 per PHP-FPM
EXPOSE 9000

# Avvio PHP
CMD ["php-fpm"]