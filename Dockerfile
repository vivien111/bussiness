# Utiliser une image de PHP avec Apache
FROM php:8.0-apache

# Activer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Copier le projet dans le répertoire du conteneur
COPY . /var/www/html/

# Changer les permissions sur les fichiers
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80
EXPOSE 80
