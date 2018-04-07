start_dev:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d

start_prod:
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
