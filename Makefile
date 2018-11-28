# Makefile for Docker Nginx PHP Composer MySQL

PATH := C:\Program Files\Oracle\VirtualBox\:node_modules/.bin:$(PATH)

include .env

# Database dumps
DB_DUMPS_DIR=storage/db/dumps

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  share        Share folder on same partition to docker work"
	@echo "  term         Open docker terminal (Windows 7 Only)"
	@echo "  start        Create and start containers"
	@echo "  populate     Recreate and populate database"
	@echo "  update       Update PHP dependencies with composer"
	@echo "  autoload     Update PHP autoload files"
	@echo "  test         Test application"
	@echo "  cover        Test application and generate coverage files"
	@echo "  check        Check the API with PHP Code Sniffer (PSR2)"
	@echo "  fix          Fix php files code standard using PSR2"
	@echo "  dump         Create backup of whole database"
	@echo "  restore      Restore backup from whole database"
	@echo "  class        Generate initial code from template files"
	@echo "  clean        Stop docker and clean generated folder"
	@echo "  purge        Clean and remove vendor folder"
	@echo "  doc          Generate documentation of API"
	@echo "  logs         Follow log output"
	@echo "  stop         Stop and clear all services"

init:
	@echo "Initializing..."
	@mkdir -p $(DB_DUMPS_DIR)
	@mkdir -p storage/logs
	@mkdir -p storage/app/cache
	@mkdir -p storage/app/compiled
	@mkdir -p public/static/doc/conta
	@mkdir -p public/static/doc/cert
	@mkdir -p public/static/img/categoria
	@mkdir -p public/static/img/cliente
	@mkdir -p public/static/img/produto
	@mkdir -p public/static/img/patrimonio

doc:
	@docker-compose exec -T php ./vendor/bin/apigen generate app --destination docs/api
	@make -s reset

share:
	vboxmanage sharedfolder add "default" --name "d/Development/Projects/grandchef.api" --hostpath "\\\\\?\D:\Development\Projects\grandchef.api" --automount

term:
	@utils\docker-term $(CURDIR)

clean: stop
	@rm -Rf storage
	@rm -Rf public/static/doc/conta
	@rm -Rf public/static/doc/cert
	@rm -Rf public/static/img/categoria
	@rm -Rf public/static/img/patrimonio/*.*
	@rm -Rf docs/api

purge: clean
	@rm -Rf node_modules
	@rm -Rf vendor
	@rm -Rf composer.lock

check:
	@echo "Checking the standard code..."
	@docker-compose exec -T php ./vendor/bin/phpcs --standard=PSR2 app tests/MZ

fix:
	@echo "Fixing to standard code..."
	@docker-compose exec -T php ./vendor/bin/phpcbf --standard=PSR2 app tests/MZ

update:
	@docker run --rm -v $(shell pwd):/app composer update --ignore-platform-reqs --no-scripts

autoload:
	@docker run --rm -v $(shell pwd):/app composer dump-autoload --no-scripts

start: init reset
	@cp etc/nginx/default.conf.template etc/nginx/grandchef.location
	@npm run fix-loc
	docker-compose up -d

stop:
	@docker-compose down -v

logs:
	@docker-compose logs -f

populate:
	@mkdir -p $(DB_DUMPS_DIR)
	@echo "SET NAMES 'utf8' COLLATE 'utf8_unicode_ci';" > $(DB_DUMPS_DIR)/populate.sql
	@cat database/model/script.sql >> $(DB_DUMPS_DIR)/populate.sql
	@cat database/model/insert.sql >> $(DB_DUMPS_DIR)/populate.sql
	@cat database/model/populate.sql >> $(DB_DUMPS_DIR)/populate.sql
	@npm run fix-pop "$(DB_NAME)"
	@make -s reset
	@docker exec -i $(shell docker-compose ps -q db) mysql -u"$(DB_ROOT_USER)" -p"$(DB_ROOT_PASSWORD)" < $(DB_DUMPS_DIR)/populate.sql

dump:
	@mkdir -p $(DB_DUMPS_DIR)
	@docker exec $(shell docker-compose ps -q db) mysqldump -B "$(DB_NAME)" -u"$(DB_ROOT_USER)" -p"$(DB_ROOT_PASSWORD)" --add-drop-database > $(DB_DUMPS_DIR)/db.sql
	@make -s reset

restore:
	@docker exec -i $(shell docker-compose ps -q db) mysql -u"$(DB_ROOT_USER)" -p"$(DB_ROOT_PASSWORD)" < $(DB_DUMPS_DIR)/db.sql

test:
	@docker-compose exec -T php ./vendor/bin/phpunit --configuration ./ --no-coverage

cover:
	@docker-compose exec -T php ./vendor/bin/phpunit --configuration ./ --coverage-html storage/coverage

class:
	@mkdir -p $(DB_DUMPS_DIR)
	@npm run fix-script
	@cp -f database/model/script.sql $(DB_DUMPS_DIR)/script_no_trigger.sql
	@npm run fix-sql
	@java -jar utils/SQLtoClass.jar -p utils/config.properties -t utils/template -o storage/app/generated

reset:
	@chmod 777 storage
	@chmod 777 storage/logs
	@chmod 777 storage/app/cache
	@chmod 777 storage/app/compiled
	@chmod 777 public/static/doc/conta
	@chmod 777 public/static/doc/cert
	@chmod 777 public/static/img/categoria
	@chmod 777 public/static/img/cliente
	@chmod 777 public/static/img/patrimonio
	@chmod 777 public/static/img/produto
	@chmod 777 public/static/img/header

.PHONY: clean test check init
