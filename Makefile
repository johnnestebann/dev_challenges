up:
	@docker-compose up -d

down:
	@docker-compose stop

test:
	@docker-compose exec backend ./vendor/bin/phpunit --testdox

sniff:
	@docker-compose exec backend ./vendor/bin/phpcbf

stan:
	@docker-compose exec backend ./vendor/bin/phpstan analyse src tests
