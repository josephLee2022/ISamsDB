# Use the official PHP 8.2 Apache image
FROM php:apache

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy your application files to the container
COPY ./src /var/www/html

# Set the working directory
WORKDIR /var/www/html
