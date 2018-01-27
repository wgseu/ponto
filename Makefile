# Makefile for Docker Nginx PHP Composer MySQL

include .env

# MySQL
MYSQL_DUMPS_DIR=storage/db/dumps

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  start        Create and start containers"
	@echo "  populate     Recreate and populate database"
	@echo "  update       Update PHP dependencies with composer"
	@echo "  autoload     Update PHP autoload files"
	@echo "  test         Test application"
	@echo "  check        Check the API with PHP Code Sniffer (PSR2)"
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
	@mkdir -p $(MYSQL_DUMPS_DIR)
	@mkdir -p public/include/compiled
	@mkdir -p public/static/doc/conta
	@mkdir -p public/static/doc/cert
	@mkdir -p public/static/img/categoria
	@mkdir -p public/static/img/cliente
	@mkdir -p public/static/img/patrimonio
	@mkdir -p public/static/img/produto
	@mkdir -p public/static/img/header

doc:
	@docker-compose exec -T php ./public/include/vendor/bin/apigen generate app --destination docs/api
	@make -s reset

clean: stop
	@rm -Rf storage
	@rm -Rf public/include/compiled
	@rm -Rf public/static/doc/conta
	@rm -Rf public/static/doc/cert
	@rm -Rf public/static/img/categoria
	@rm -Rf public/static/img/cliente
	@rm -Rf public/static/img/patrimonio
	@rm -Rf public/static/img/produto
	@rm -Rf public/static/img/header
	@rm -Rf docs/api

purge: clean
	@rm -Rf public/include/vendor
	@rm -Rf composer.lock

check:
	@echo "Checking the standard code..."
	@docker-compose exec -T php ./public/include/vendor/bin/phpcs --standard=PSR2 public/include/api public/include/classes public/include/function public/include/library public/app public/categoria public/conta public/contato public/gerenciar public/produto public/sobre

update:
	@docker run --rm -v $(shell pwd):/app composer update

autoload:
	@docker run --rm -v $(shell pwd):/app composer dump-autoload

start: init reset
	@cp etc/nginx/default.conf.template etc/nginx/grandchef.location
	@sed -i "s/\"%PUBLIC_PATH%\"/\/var\/www\/html\/public/g" etc/nginx/grandchef.location
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
	@docker exec -i $(shell docker-compose ps -q gmysqldb) mysql -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" < $(MYSQL_DUMPS_DIR)/db.sql 2>/dev/null

test:
	@docker-compose exec -T php ./public/include/vendor/bin/phpunit --colors=always --configuration ./ --no-coverage ./tests

class:
	@mkdir -p $(MYSQL_DUMPS_DIR)
	@cp -f database/model/script.sql 														$(MYSQL_DUMPS_DIR)/script_no_trigger.sql
	@perl -0777 -i.original -pe "s/USE \`GrandChef\`\\$$\\\$$\r?\n//igs" 					$(MYSQL_DUMPS_DIR)/script_no_trigger.sql
	@perl -0777 -i.original -pe "s/USE \`GrandChef\`;\r?\n//igs" 							$(MYSQL_DUMPS_DIR)/script_no_trigger.sql
	@perl -0777 -i.original -pe "s/END\\\$$\\\$$\r?\n/END \\\$$\\\$$/igs" 					$(MYSQL_DUMPS_DIR)/script_no_trigger.sql
	@perl -0777 -i.original -pe "s/\`GrandChef\`\.//igs" 									$(MYSQL_DUMPS_DIR)/script_no_trigger.sql
	@perl -0777 -i.original -pe "s/\r?\nDELIMITER \\\$$\\\$$.*?DELIMITER ;\r?\n\r?\n//igs" 	$(MYSQL_DUMPS_DIR)/script_no_trigger.sql
	@perl -0777 -i.original -pe "s/' \/\* comment truncated \*\/ \/\*([^\*]+)\*\//\1'/igs"	$(MYSQL_DUMPS_DIR)/script_no_trigger.sql
	@perl -0777 -i.original -pe "s/([^\\\\\][\\\\\])([^\\\\\'])/\1\\\\\\\\\2/igs"			$(MYSQL_DUMPS_DIR)/script_no_trigger.sql
	@rm -f $(MYSQL_DUMPS_DIR)/script_no_trigger.sql.original
	@java -jar utils/SQLtoClass.jar -p utils/config.properties -t utils/template -o storage/app/generated

reset:
	@chmod 777 public/include/compiled
	@chmod 777 public/static/doc/conta
	@chmod 777 public/static/doc/cert
	@chmod 777 public/static/img/categoria
	@chmod 777 public/static/img/cliente
	@chmod 777 public/static/img/patrimonio
	@chmod 777 public/static/img/produto
	@chmod 777 public/static/img/header

.PHONY: clean test check init
