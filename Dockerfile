# Giai đoạn 1: Build Frontend (Vite)
FROM node:24 AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Giai đoạn 2: Cấu hình PHP & Apache
FROM php:8.2-apache

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl

# Cài đặt PHP extensions cho Laravel & MySQL
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Cấu hình Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copy code và các file đã build từ Vite
WORKDIR /var/www/html
COPY . .
COPY --from=frontend-builder /app/public/build ./public/build

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền cho Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
