.DEFAULT_GOAL := help
.PHONY: env-copy docker-build docker-up install start stop restart test-start test-stop test-install test

DOCKER_COMPOSE=docker-compose
APP=$(DOCKER_COMPOSE) exec -u www-data app

# Docker
docker-build:
	$(DOCKER_COMPOSE) build

docker-up:
	$(DOCKER_COMPOSE) up -d

docker-stop:
	$(DOCKER_COMPOSE) kill

docker-clean:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) rm -fv

# Project
project-install:
	$(APP) composer install
	$(APP) dockerize -wait tcp://mysql:3306 -wait tcp://elasticsearch:9200 -timeout 60s
	$(APP) bin/console doctrine:schema:update --force
	$(APP) bin/console hautelook:fixtures:load -n

project-fixtures:
	$(APP) bin/console hautelook:fixtures:load -n

populate:
	$(APP) bin/console fos:elastica:populate

cs:
	$(APP) vendor/bin/php-cs-fixer fix

# Main
install: docker-build docker-up project-install

start: docker-up

stop: docker-stop

restart: docker-clean install
