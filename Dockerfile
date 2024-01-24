FROM php:7.4-apache


# Dependencies
RUN apt-get update && \
    apt-get install \
    git \
    curl \
    libzip-dev\
    libpng-dev \
    libonig-dev \
    libldap2-dev \
    libxml2-dev \
    unzip -y && \
    docker-php-ext-install zip pdo_mysql mbstring bcmath gd exif pcntl ldap 

# Composer
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    mv "/usr/local/etc/php/php.ini-production" "/usr/local/etc/php/php.ini" && \
    cd ~ && \
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && \
    HASH=`curl -sS https://composer.github.io/installer.sig` && \
    php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    mkdir tetfund

# Set working directory
WORKDIR /var/www/tetfund/
COPY ./000-default.conf /etc/apache2/sites-enabled/000-default.conf

# Rewriting Apache
RUN  a2enmod rewrite && \
     service apache2 restart
 

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data

 








