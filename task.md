# Les commandes a faire : 
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve


# Docker 

``` bash
    docker compose up : pour demarer le container 
    docker compose down : il arrete le container si a la fin  de la commmande tu ajoutes '-v',il va supprimer les volumes du container 
```
