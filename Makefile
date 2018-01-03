# Makefile for Docker Nginx PHP Composer MySQL

include .env

# MySQL
MYSQL_DUMPS_DIR=storage/db/dumps

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  doc                 Generate documentation of API"
	@echo "  check               Check the API with PHP Code Sniffer (PSR2)"
	@echo "  clean               Clean directories for reset"
	@echo "  update              Update PHP dependencies with composer"
	@echo "  start               Create and start containers"
	@echo "  stop                Stop and clear all services"
	@echo "  logs                Follow log output"
	@echo "  populate            Recreate and populate database"
	@echo "  dump                Create backup of whole database"
	@echo "  restore             Restore backup from whole database"
	@echo "  test                Test application"
	@echo "  class               Generate initial code from template files (Run utils/fix_script before)"

init:
	@echo "Initializing..."

doc:
	@docker-compose exec -T php ./src/include/vendor/bin/apigen generate app --destination docs/api
	@make -s reset

clean:
	@rm -Rf storage/db/mysql
	@rm -Rf $(MYSQL_DUMPS_DIR)
	@rm -Rf src/include/vendor
	@rm -Rf composer.lock
	@rm -Rf docs/api

check:
	@echo "Checking the standard code..."
	@docker-compose exec -T php ./src/include/vendor/bin/phpcs --standard=PSR2 src/include/api src/include/classes src/include/function src/include/library src/app src/categoria src/conta src/contato src/gerenciar src/produto src/sobre

update:
	@docker run --rm -v $(shell pwd):/app composer update

autoload:
	@docker run --rm -v $(shell pwd):/app composer dump-autoload

start: init
	@cp etc/nginx/default.conf.template etc/nginx/grandchef.location
	@sed -i "s/\"%PUBLIC_PATH%\"/\/var\/www\/html\/src/g" etc/nginx/grandchef.location 
	@sed -i "s/127.0.0.1:9456;/php:9000;/g" etc/nginx/grandchef.location
	docker-compose up -d

stop:
	@docker-compose down -v

logs:
	@docker-compose logs -f

populate:
	@$(shell mkdir -p $(MYSQL_DUMPS_DIR))
	@$(shell echo "SET NAMES 'utf8' COLLATE 'utf8_unicode_ci';" > $(MYSQL_DUMPS_DIR)/populate.sql)
	@$(shell cat database/model/script.sql >> $(MYSQL_DUMPS_DIR)/populate.sql)
	@$(shell cat database/model/insert.sql >> $(MYSQL_DUMPS_DIR)/populate.sql)
	@$(shell perl -0777 -i.original -pe "s/\`GrandChef\`/\`$(MYSQL_DATABASE)\`/igs" $(MYSQL_DUMPS_DIR)/populate.sql)
	@rm -f $(MYSQL_DUMPS_DIR)/populate.sql.original
	@make -s reset
	@docker exec -i $(shell docker-compose ps -q gmysqldb) mysql -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" < $(MYSQL_DUMPS_DIR)/populate.sql 2>/dev/null

dump:
	@mkdir -p $(MYSQL_DUMPS_DIR)
	@docker exec $(shell docker-compose ps -q gmysqldb) mysqldump -B "$(MYSQL_DATABASE)" -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" --add-drop-database > $(MYSQL_DUMPS_DIR)/db.sql 2>/dev/null
	@make -s reset

restore:
	@$(shell perl -0777 -i.original -pe "s/\`mzsw\`/\`$(MYSQL_DATABASE)\`/igs" $(MYSQL_DUMPS_DIR)/db.sql)
	@rm -f $(MYSQL_DUMPS_DIR)/db.sql.original
	@docker exec -i $(shell docker-compose ps -q gmysqldb) mysql -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" < $(MYSQL_DUMPS_DIR)/db.sql 2>/dev/null

test:
	@docker-compose exec -T php ./src/include/vendor/bin/phpunit --colors=always --configuration ./ --no-coverage ./tests
	@make -s reset

class:
	@java -jar utils/SQLtoClass.jar -p utils/config.properties -t utils/template -o utils/tmp/generated

reset: ;

.PHONY: clean test check init