# CDA-symfony

### Mise en place

- Dupliquer le ficher .env.test et le renomer en .env

Dans le docker php :
- composer install
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate
- php bin/console doctrine:fixture:load

### Accés
acceder à api plateforme : http://localhost:1030/api
acceder au Back-end : http://localhost:1030
acceder au React : http://localhost:3000
