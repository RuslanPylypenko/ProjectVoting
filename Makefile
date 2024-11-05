up: docker-up
init: docker-down-clear docker-pull docker-build docker-up api-init api-fixtures
build: docker-build
down: docker-down

docker-up:
	docker compose up -d

docker-build:
	docker compose build

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

api-init: api-composer-install api-wait-db api-migrations

api-fixtures:
	docker compose run --rm api-php-fpm php bin/console doctrine:fixtures:load --no-interaction

api-append-projects:
	docker compose run --rm api-php-fpm php bin/console doctrine:fixtures:load --group=projects --append --no-interaction

api-test:
	docker compose run --rm api-php-fpm ./vendor/bin/phpunit

api-migrations:
	docker compose run --rm api-php-fpm composer app do:mi:mi --no-interaction

api-composer-install:
	docker compose run --rm api-php-fpm composer install

api-wait-db:
	docker compose run --rm api-php-fpm wait-for-it api-mysql:3306 -t 30

bash:
	docker compose exec api-php-fpm bash