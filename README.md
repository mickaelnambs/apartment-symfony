## Installation
```
git clone https://github.com/mickaelnambs/apartment
cd apartment
composer install
```

## Configuration
Créer un fichier `.env.local` : 
```dotenv
DATABASE_URL=mysql://root:password@127.0.0.1:3306/apartment
```

## Configuration de la base de donnée et migration
```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## Démarrer le serveur
```
symfony serve
```
