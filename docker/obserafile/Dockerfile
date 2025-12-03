# Use the official PHP image with Apache
FROM php:8.2-apache

# 1. Install system dependencies required for GD and Composer
# zip, unzip, and git are often needed by Composer
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# 2. Configure and install the GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# 3. Install database extensions (from previous requirement)
RUN docker-php-ext-install mysqli pdo pdo_mysql zip

# 4. Enable Apache mod_rewrite
RUN a2enmod rewrite

# --- NEW SECTION: Change Document Root to /public ---
ENV APACHE_DOCUMENT_ROOT /var/www/html/public_html

# Update the default apache site with the config we created.
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# ----------------------------------------------------

# 5. Install Composer globally
# We copy the executable from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Update permissions
RUN chown -R www-data:www-data /var/www/html
