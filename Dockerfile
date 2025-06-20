FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet
COPY . /var/www/html

# Définir le répertoire de travail
WORKDIR /var/www/html/public

# Donner les permissions
RUN chown -R www-data:www-data /var/www/html

# Activer mod_rewrite d’Apache (utile pour Symfony)
RUN a2enmod rewrite

# Ajouter une config Apache pour Symfony
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
</Directory>' > /etc/apache2/conf-available/symfony.conf && \
    a2enconf symfony

# 🆕 Rediriger Apache vers /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
