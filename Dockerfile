# Imagem base com PHP e extensões
FROM php:8.1-fpm

# Instalar dependências
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos
COPY . .

# Permissões corretas
RUN chmod -R 775 storage bootstrap/cache

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Configurar porta de escuta
EXPOSE 9000

CMD ["php-fpm"]
