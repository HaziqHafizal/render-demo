# 1. Use PHP 8.2 with Apache
FROM php:8.2-apache

# 2. Install Linux libraries needed for Laravel & PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev

# 3. Install PHP extensions (MySQL for local, PgSQL for Render)
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# 4. Enable Apache rewrite module (essential for Laravel routes)
RUN a2enmod rewrite

# 5. Set the working directory
WORKDIR /var/www/html

# 6. Copy all your files into the container
COPY . .

# 7. Install Composer (the PHP package manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8. Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# 9. Fix permission issues
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Point Apache to the 'public' folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 11. Open port 80
EXPOSE 80