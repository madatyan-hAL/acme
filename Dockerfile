# Use the official PHP image from the Docker Hub
FROM php:8.2-apache

# Install other extensions if needed
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install dependencies and enable PHP extensions
RUN apt-get update && \
    apt-get install -y libzip-dev unzip && \
    docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Composer configuration files
COPY composer.json composer.lock /var/www/html/

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy custom PHP configuration file (optional)
COPY config/php.ini /usr/local/etc/php/

# Enable Apache mod_rewrite for URL rewriting
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html/

# Set permissions (optional, depending on your setup)
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html
