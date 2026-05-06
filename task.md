# Système Parquet — Commandes & URLs

---

## Installation (première fois)

```bash
composer install
cp .env.example .env # Linux
copy .env.example .env # Windows
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve # Pour demarer le projet
```

---

## Docker

```bash
# Démarrer tous les containers
docker compose up

# Démarrer en arrière-plan (détaché)
docker compose up -d

# Arrêter les containers
docker compose down

# Arrêter ET supprimer les volumes (reset complet BDD + stockage)
docker compose down -v

# Voir les logs en temps réel
docker compose logs -f

# Voir les containers actifs
docker compose ps
```

---

## Laravel (Artisan)

```bash
# Lancer le serveur de développement
php artisan serve

# Migrations
php artisan migrate
php artisan migrate --seed        # avec les seeders
php artisan migrate:fresh --seed  # reset complet + seeders

# Cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Lister toutes les routes
php artisan route:list

# Créer des fichiers
php artisan make:controller NomController
php artisan make:model NomModel -m       # avec migration
php artisan make:migration nom_migration
php artisan make:seeder NomSeeder

# Lien storage public
php artisan storage:link
```

---

## URLs du projet

| Service         | URL                        | Description                        |
|-----------------|----------------------------|------------------------------------|
| Application     | http://localhost:8000      | Laravel (php artisan serve)        |
| phpMyAdmin      | http://localhost:8081      | Interface base de données MySQL    |
| MinIO Console   | http://localhost:9002      | Interface d'administration MinIO   |
| MinIO API S3    | http://localhost:9001      | Endpoint S3 (utilisé par Laravel)  |

---

## Base de données (MySQL via Docker)

```bash
# Connexion MySQL directe dans le container
docker exec -it systeme_parquet_db mysql -u laravel -p

# Port exposé sur la machine hôte
Host     : 127.0.0.1
Port     : 3307
Database : parquet
Username : laravel
```

---

## MinIO (stockage fichiers)

```
Console web  : http://localhost:9002
API S3       : http://localhost:9001
User         : voir MINIO_ROOT_USER dans .env
Password     : voir MINIO_ROOT_PASSWORD dans .env
```

---

## Comptes par défaut (après seed)

> Vérifier dans `database/seeders/` pour les identifiants exacts.

| Rôle      | Email                    | Mot de passe |
|-----------|--------------------------|--------------|
| Admin     | admin@parquet.sn         | à vérifier   |
| Procureur | procureur@parquet.sn     | à vérifier   |
| Juge      | juge@parquet.sn          | à vérifier   |
| Greffier  | greffier@parquet.sn      | à vérifier   |
