# Taff - Systeme Parquet

## 2026-05-27

### Objectif de cette passe

Corriger le stockage et la visualisation des pieces jointes des dossiers, sans toucher au `docker-compose.yml`, puis garder une trace claire de l'avancement par rapport au cahier de charge.

### Travaux realises

- Ajout de la dependance Laravel/Flysystem necessaire pour utiliser MinIO/S3 :
  - `league/flysystem-aws-s3-v3`
  - dependances AWS associees dans `composer.lock`
- Correction du stockage des PDF lors de la creation d'un dossier :
  - avant : stockage force dans le disque `public`
  - maintenant : stockage dans le disque configure par `FILESYSTEM_DISK`
  - avec `.env`, cela cible `minio`
- Conservation d'une compatibilite avec les anciens fichiers deja stockes en `public`.
- Ajout d'une page detail dossier :
  - informations generales du dossier
  - liste des parties
  - liste des pieces jointes
  - previsualisation du premier PDF dans la page
- Ajout d'une route de visualisation PDF en inline :
  - les fichiers restent dans le stockage
  - l'application sert le PDF dans le navigateur
- Mise a jour des listes de dossiers :
  - le numero de dossier et le bouton Voir ouvrent maintenant la page detail
- Ajout d'un message d'erreur visible si le stockage MinIO echoue lors de l'upload.

### Fichiers modifies

- `composer.json`
- `composer.lock`
- `app/Http/Controllers/DossierController.php`
- `routes/web.php`
- `resources/views/dossiers/show.blade.php`
- `resources/views/admin/dossiers/index.blade.php`
- `resources/views/greffier/dossier/index.blade.php`
- `resources/views/procureur/dossier/index.blade.php`
- `resources/views/juge/dossier/index.blade.php`
- `resources/views/greffier/dossier/ajout.blade.php`
- `resources/views/procureur/dossier/ajout.blade.php`
- `resources/views/juge/dossier/ajout.blade.php`

### Points a verifier sur ton environnement local

- MinIO doit etre lance via Docker.
- Le bucket `parquet` doit exister dans MinIO.
- Le `.env` doit bien garder `FILESYSTEM_DISK=minio`.
- Si la configuration Laravel est cachee, lancer :
  - `php artisan config:clear`
  - `php artisan cache:clear`

### Limite de verification cote Codex

Le terminal Codex utilise PHP 8.0.30 alors que le projet exige PHP 8.2+. Les commandes Artisan ne peuvent donc pas etre executees ici. La verification fonctionnelle se fait via l'application deja lancee sur `http://localhost:8001`.

### Prochaines fonctionnalites du cahier

Priorite 1 :
- Finaliser le detail dossier avec edition et suppression controlee.
- Stabiliser la separation des roles admin, greffier, procureur et juge.
- Ajouter une vraie numerotation sequentielle par registre.

Priorite 2 :
- Module instruction et suivi par le parquet.
- Decision d'orientation : classement, citation, comparution immediate, requisitoire.
- Historique horodate des actions du dossier.

Priorite 3 :
- Preparation audience.
- Audience et decision judiciaire.
- Execution des decisions.
- Archivage et statistiques.

