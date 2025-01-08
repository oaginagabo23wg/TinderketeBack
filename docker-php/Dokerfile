FROM php:8.2-fpm

# php.ini
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Installing dependencies for the PHP modules
RUN apt-get update && \
    apt-get install -y zip curl libcurl3-dev libzip-dev libpng-dev libonig-dev libxml2-dev
    # libonig-dev is needed for oniguruma which is needed for mbstring

# Installing additional PHP modules
RUN docker-php-ext-install curl gd mbstring mysqli pdo pdo_mysql xml

# Install and configure ImageMagick
RUN apt-get install -y libmagickwand-dev
RUN pecl install imagick
RUN docker-php-ext-enable imagick
RUN apt-get purge -y libmagickwand-dev

# Install Composer so it's available
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# npm
RUN curl -sL https://deb.nodesource.com/setup_20.x -o nodesource_setup.sh
RUN ["sh",  "./nodesource_setup.sh"]

RUN apt-get install -y nodejs
#RUN npm install npm@latest -g

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www/html

USER $user

#install laravel
RUN composer global require laravel/installer
#ENV PATH="$PATH:/root/.config/composer/vendor/bin"
ENV PATH="$PATH:/home/$user/.composer/vendor/bin"
ENV DB_HOST=db
ENV DB_USER=root
ENV DB_PASSWORD=root
    
