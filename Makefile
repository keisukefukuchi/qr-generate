.PHONY: up down build restart ps logs shell env-copy key-generate pint storage-link

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build
	cp .env.example .env
	docker-compose up -d
	docker-compose exec app composer install
	docker-compose exec app php artisan key:generate --ansi
	docker-compose exec app php artisan storage:link
	docker-compose exec app php artisan migrate

restart:
	docker-compose restart

ps:
	docker-compose ps

logs:
	docker-compose logs -f

shell:
	docker-compose exec app bash

migrate:
	docker-compose exec app php artisan migrate

migrate-fresh:
	docker-compose exec app php artisan migrate:fresh --seed

pint:
	docker-compose exec app ./vendor/bin/pint

clear:
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan view:clear