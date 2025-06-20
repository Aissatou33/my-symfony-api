FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    curl \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    intl \
    opcache \
    zip

# Installer le Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet
COPY . /var/www/html

# Installer les dépendances PHP de Symfony
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

# Configurer Apache pour Symfony
COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite \
    && a2dissite 000-default.conf \
    && a2ensite 000-default.conf \
    && echo '<Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>' > /etc/apache2/conf-available/symfony.conf \
    && a2enconf symfony

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/var

# Définir le répertoire de travail (optionnel, car Apache gère cela)
WORKDIR /var/www/html

# Exposer le port 80 (requis par Render)
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]