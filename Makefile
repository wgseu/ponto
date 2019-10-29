# Makefile for Docker Nginx PHP Composer MySQL

PATH := node_modules/.bin:$(PATH)

include .env

export DEBUG_BACK
export DEBUG_HOST
export CURRENT_UID

export PROXY_HOST

CURRENT_UID= $(shell id -u):$(shell id -g)

# Database dumps
DB_DUMPS_DIR=storage/db/dumps

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  start        Create and start containers"
	@echo "  migrate      Run new migrations"
	@echo "  downgrade    Downgrade migrations"
	@echo "  seeds         Run seeds"
	@echo "  update       Update PHP dependencies with composer"
	@echo "  autoload     Update PHP autoload files"
	@echo "  test         Test application"
	@echo "  cover        Test application and generate coverage output"
	@echo "  report       Test application and generate coverage report files"
	@echo "  check        Check the API with PHP Code Sniffer (PSR2)"
	@echo "  fix          Fix php files code standard using PSR2"
	@echo "  dump         Create backup of whole database"
	@echo "  restore      Restore backup from whole database"
	@echo "  class        Generate initial code from template files"
	@echo "  clean        Stop docker and clean generated folder"
	@echo "  purge        Clean and remove vendor folder"
	@echo "  doc          Generate documentation of API"
	@echo "  stop         Stop and clear all services"

init:
	@echo "Initializing..."
	@mkdir -p $(DB_DUMPS_DIR)
	@mkdir -p storage/logs
	@mkdir -p storage/app/cache
	@mkdir -p storage/app/compiled
	@mkdir -p storage/framework/cache
	@mkdir -p storage/framework/views
	@mkdir -p storage/framework/sessions
	@mkdir -p storage/framework/testing

doc: reset
	@docker-compose exec -T php ./vendor/bin/apigen generate app --destination docs/api

clean: stop

purge: clean
	@rm -Rf node_modules
	@rm -Rf vendor

check:
	@echo "Checking the standard code..."
	@docker-compose exec -T php ./vendor/bin/phpcs --standard=PSR2 app tests

fix:
	@echo "Fixing to standard code..."
	@docker-compose exec -T php ./vendor/bin/phpcbf --standard=PSR2 app tests

update:
	@docker run --rm \
		-u $(CURRENT_UID) \
		-v $(shell pwd):/app \
		-v /etc/passwd:/etc/passwd:ro \
		-v /etc/group:/etc/group:ro \
		roquie/composer-parallel update -n --ignore-platform-reqs --no-scripts -vvv

autoload:
	@docker run --rm \
		-u $(CURRENT_UID) \
		-v $(shell pwd):/app \
		-v /etc/passwd:/etc/passwd:ro \
		-v /etc/group:/etc/group:ro \
		roquie/composer-parallel dump-autoload -n --no-scripts

start: init
	envsubst '$$DEBUG_BACK $$DEBUG_HOST' < ./etc/php/php.template.ini > ./etc/php/php.ini
	docker-compose up -d

stop:
	@docker-compose down -v

migrate:
	@docker exec -i $(shell CURRENT_UID=$(CURRENT_UID) docker-compose ps -q php) ./artisan migrate

downgrade:
	@docker exec -i $(shell CURRENT_UID=$(CURRENT_UID) docker-compose ps -q php) ./artisan migrate:rollback

seeds:
	@docker exec -i $(shell CURRENT_UID=$(CURRENT_UID) docker-compose ps -q php) ./artisan db:seed

dump:
	@mkdir -p $(DB_DUMPS_DIR)
	@docker exec $(shell CURRENT_UID=$(CURRENT_UID) docker-compose ps -q db) mysqldump -B "$(DB_DATABASE)" -u"$(DB_ROOT_USER)" -p"$(DB_ROOT_PASSWORD)" --add-drop-database > $(DB_DUMPS_DIR)/db.sql

restore:
	@docker exec -i $(shell CURRENT_UID=$(CURRENT_UID) docker-compose ps -q db) mysql -u"$(DB_ROOT_USER)" -p"$(DB_ROOT_PASSWORD)" < $(DB_DUMPS_DIR)/db.sql

test:
	@docker-compose exec -T php ./vendor/bin/phpunit --configuration . --no-coverage --colors=always

cover:
	@docker-compose exec -T php ./vendor/bin/phpunit --configuration . --colors=always

report:
	@docker-compose exec -T php ./vendor/bin/phpunit --configuration . --coverage-html storage/coverage --colors=always

class:
	@java -jar utils/SQLtoClass.jar -p utils/config.properties -t utils/template -o storage/generated

.PHONY: clean test check init

restart: stop start
