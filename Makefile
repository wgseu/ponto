# Makefile for Docker Nginx PHP Composer MySQL

include .env

# MySQL
MYSQL_DUMPS_DIR=storage/db/dumps

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  apidoc              Generate documentation of API"
	@echo "  code-sniff          Check the API with PHP Code Sniffer (PSR2)"
	@echo "  clean               Clean directories for reset"
	@echo "  composer-up         Update PHP dependencies with composer"
	@echo "  docker-start        Create and start containers"
	@echo "  docker-stop         Stop and clear all services"
	@echo "  logs                Follow log output"
	@echo "  populate            Recreate and populate database"
	@echo "  mysql-dump          Create backup of whole database"
	@echo "  mysql-restore       Restore backup from whole database"
	@echo "  test                Test application"

init:
	@echo "Initializing..."

apidoc:
	@docker-compose exec -T php ./vendor/bin/apigen generate app --destination docs/api
	@make -s resetOwner

clean:
	@rm -Rf storage/db/mysql
	@rm -Rf $(MYSQL_DUMPS_DIR)
	@rm -Rf vendor
	@rm -Rf composer.lock
	@rm -Rf docs/api

code-sniff:
	@echo "Checking the standard code..."
	@docker-compose exec -T php ./vendor/bin/phpcs -v --standard=PSR2 app

composer-up:
	@docker run --rm -v $(shell pwd):/app composer update

docker-start: init
	@cp etc/nginx/default.conf.template etc/nginx/grandchef.location
	@sed -i 's/\"%PUBLIC_PATH%\"/\/var\/www\/html\/src/g' etc/nginx/grandchef.location 
	@sed -i 's/:9456;/:9000;/g' etc/nginx/grandchef.location
	docker-compose up -d

docker-stop:
	@docker-compose down -v

logs:
	@docker-compose logs -f

populate:
	@$(shell mkdir -p $(MYSQL_DUMPS_DIR))
	@$(shell cat database/model/script.sql > $(MYSQL_DUMPS_DIR)/populate.sql)
	@$(shell cat database/model/insert.sql >> $(MYSQL_DUMPS_DIR)/populate.sql)
	@$(shell perl -0777 -i.original -pe "s/\`mzsw\`/\`$(MYSQL_DATABASE)\`/igs" $(MYSQL_DUMPS_DIR)/populate.sql)
	@rm -f $(MYSQL_DUMPS_DIR)/populate.sql.original
	@make -s resetOwner
	@docker exec -i $(shell docker-compose ps -q gmysqldb) mysql -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" < $(MYSQL_DUMPS_DIR)/populate.sql 2>/dev/null

mysql-dump:
	@mkdir -p $(MYSQL_DUMPS_DIR)
	@docker exec $(shell docker-compose ps -q gmysqldb) mysqldump -B "$(MYSQL_DATABASE)" -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" --add-drop-database > $(MYSQL_DUMPS_DIR)/db.sql 2>/dev/null
	@make -s resetOwner

mysql-restore:
	@$(shell perl -0777 -i.original -pe "s/\`mzsw\`/\`$(MYSQL_DATABASE)\`/igs" $(MYSQL_DUMPS_DIR)/db.sql)
	@rm -f $(MYSQL_DUMPS_DIR)/db.sql.original
	@docker exec -i $(shell docker-compose ps -q gmysqldb) mysql -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" < $(MYSQL_DUMPS_DIR)/db.sql 2>/dev/null

test: code-sniff
	@docker-compose exec -T php ./vendor/bin/phpunit --colors=always --configuration ./
	@make -s resetOwner

resetOwner: ;

.PHONY: clean test code-sniff init