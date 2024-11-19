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
	docker compose run --rm api-php-cli php bin/console doctrine:fixtures:load --no-interaction

api-test:
	docker compose run --rm api-php-cli ./vendor/bin/phpunit

api-migrations:
	docker compose run --rm api-php-cli php bin/console do:mi:mi --no-interaction

api-composer-install:
	docker compose run --rm api-php-cli composer install

api-wait-db:
	docker compose run --rm api-php-cli wait-for-it api-mysql:3306 -t 30

validate:
	docker compose run --rm api-php-cli ./vendor/bin/php-cs-fixer fix

bash:
	docker compose exec api-php-cli bash