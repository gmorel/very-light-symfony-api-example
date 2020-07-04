#!/bin/bash -e

# Containers
docker-compose -f docker-compose.yml up -d --remove-orphans

# Databases
docker-compose exec php bin/console doctrine:database:drop --force --if-exists

docker-compose exec php bin/console doctrine:database:create --if-not-exists

docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
