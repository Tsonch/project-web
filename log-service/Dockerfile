# Базовый образ: PHP 8.0 с Apache
FROM php:8.0-apache

# Установка системных зависимостей для PostgreSQL и cURL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    pkg-config \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*  # Очистка кэша

# Копирование файлов сервиса в рабочую директорию Apache
COPY . /var/www/html

# Права на файлы
RUN chown -R www-data:www-data /var/www/html

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Экспорт порта 80
EXPOSE 80

# Команда запуска: Apache в foreground
CMD ["apache2-foreground"]

