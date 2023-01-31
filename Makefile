install: rebuild-docker prepare

start: up

stop: down

restart: down up

rebuild-docker: down build start

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

bash:
	docker-compose exec --user=www-data php-fpm bash

prepare:
	docker-compose exec php-fpm composer install --prefer-dist --no-progress --no-interaction
	docker-compose exec php-fpm bin/console doctrine:schema:update --force

prepare-tests: prepare
	docker-compose exec php-fpm bin/console doctrine:schema:update --force --env=test

run-static:
	docker-compose exec --user=www-data php-fpm composer phpstan
	docker-compose exec --user=www-data php-fpm composer psalm
	docker-compose exec --user=www-data php-fpm composer phan
	docker-compose exec --user=www-data php-fpm composer check-style
	docker-compose exec --user=www-data php-fpm composer ecs
	docker-compose exec --user=www-data php-fpm composer phpcpd src
	docker-compose exec --user=www-data php-fpm composer phpmd
	docker-compose exec --user=www-data php-fpm composer phpinsights
