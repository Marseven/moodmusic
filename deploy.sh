#!/bin/bash
set -e

echo "=== Déploiement MoodMusic ==="

echo ">> Pull des dernières modifications..."
git pull origin main

echo ">> Installation des dépendances PHP..."
composer install --no-dev --optimize-autoloader

echo ">> Exécution des migrations..."
php artisan migrate --force

echo ">> Nettoyage des caches..."
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Déploiement terminé ==="
