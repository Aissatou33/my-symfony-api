# Backend Symfony - Gestion des Tâches

## Description
API REST développée avec Symfony pour gérer des tâches, compatible avec un frontend React. Fonctionnalités : création, liste, modification, archivage et gestion des tâches archivées.

## Pré-requis
- PHP >= 8.1
- Composer
- MySQL/PostgreSQL
- Symfony CLI (optionnel)

## Installation
1. Cloner le dépôt
2. Installer les dépendances : `composer install`
3. Configurer la base de données dans `.env`
4. Créer la base de données : `php bin/console doctrine:database:create`
5. Appliquer les migrations : `php bin/console doctrine:migration:migrate`
6. Lancer le serveur : `php bin/console server:run` ou `symfony server:start`

## Endpoints API
- `POST /api/tasks` : Créer une tâche
- `GET /api/tasks` : Liste des tâches actives
- `PUT /api/tasks/{id}` : Modifier une tâche
- `DELETE /api/tasks/{id} : Archiver une tâche
- `GET /api/tasks/archived` : Liste des tâches archivées
- `DELETE /api/tasks/archived` : Vider l’archive

## Structure
- `title` : String, obligatoire
- `status` : String ("À faire", "En cours", "Terminé"), obligatoire
- `dueDate` : DateTime, facultatif
- `description` : String, facultatif
- `priority` : String ("Basse", "Moyenne", "Haute"), facultatif
- `createdAt` : DateTime, auto-rempli
- `updatedAt` : DateTime, auto-rempli
- `author` : String, facultatif
- `isArchived` : Boolean, pour l’archivage