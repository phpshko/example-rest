init:
	-make up
	sleep 10
	-make fix-permissions
	-make config-dev
	-make install
	-make migrate
	-make generate-user
	-make start-supervisor

up:
	docker-compose up -d --build --force-recreate

start-supervisor:
	docker-compose exec php supervisord

install:
	docker-compose exec php composer install --prefer-dist

config-dev:
	php app/init --env='Development' --overwrite=a

migrate:
	docker-compose exec php php yii migrate --interactive=0

generate-user:
	docker-compose exec php php yii generate-user

stop:
	docker-compose stop

destroy:
	docker-compose down --remove-orphans

fix-permissions:
	docker-compose exec php chown -hR www-data:www-data /var/www/html/web/uploads


nginx:
	docker-compose exec nginx bash

rabbit:
	docker-compose exec rabbit sh

db:
	docker-compose exec db bash

php:
	docker-compose exec php bash



run-tests:
	-docker-compose -p test -f docker-compose.test.yml down --remove-orphans
	-docker-compose -p test -f docker-compose.test.yml up -d --build --force-recreate
	-docker-compose -p test -f docker-compose.test.yml exec php chown -hR www-data:www-data /var/www/html/web/uploads
	-sleep 10
	-docker-compose -p test -f docker-compose.test.yml exec php supervisord
	docker-compose -p test -f docker-compose.test.yml exec php sh -c "php vendor/bin/codecept run --html"

destroy-tests:
	docker-compose -p test -f docker-compose.test.yml down


test_nginx:
	docker-compose -p test -f docker-compose.test.yml exec nginx bash

test_rabbit:
	docker-compose -p test -f docker-compose.test.yml exec rabbit sh

test_db:
	docker-compose -p test -f docker-compose.test.yml exec db bash

test_php:
	docker-compose -p test -f docker-compose.test.yml exec php bash
