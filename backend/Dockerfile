FROM php:8.2-cli

# Installer les dépendances système nécessaires
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpq-dev \
    libicu-dev \
    libonig-dev \
    openssl \
 && docker-php-ext-install pdo pdo_pgsql intl \
 && rm -rf /var/lib/apt/lists/*

# Installer Composer (sans multi-stage pour limiter la taille)
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && php -r "unlink('composer-setup.php');"

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de l'application
COPY . /var/www

# Exposer le port du serveur PHP intégré
EXPOSE 8000

# Laisser docker-compose gérer le lancement (command:)
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
