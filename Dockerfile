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

# Cấu hình thư mục làm việc
WORKDIR /var/www/html
COPY . .
COPY --from=frontend-builder /app/public/build ./public/build

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# --- PHẦN CẬP NHẬT QUAN TRỌNG ---
# 1. Tạo các thư mục cần thiết mà Laravel yêu cầu để không bị lỗi 500
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p bootstrap/cache

# 2. Cấp quyền sở hữu và quyền ghi cho thư mục storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 3. Lệnh khởi động: Tự động chạy Migration trước khi bật Web Server
# sh -c cho phép chạy chuỗi lệnh cùng lúc
CMD sh -c "php artisan config:clear && apache2-foreground"
