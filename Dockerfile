# Utiliser l'image PHP avec FPM
FROM php:8.2-fpm

# Installer les dépendances nécessaires et activer les extensions PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copier le projet dans le répertoire du conteneur
COPY . /var/www

# Changer les permissions sur les fichiers
RUN chown -R www-data:www-data /var/www

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000
# Démarrer PHP-FPM