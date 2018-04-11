COMPOSE=docker-compose
COMPOSER=$(COMPOSE) run --rm composer

help:           ## Show this help
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

install:        ## Setup the project
install: build start_prod install_php_deps

build:          ## Build Docker containers
	docker-compose build

install_php_deps:
	$(COMPOSER) install

update_php_deps:
	$(COMPOSER) update

start_dev:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d

start_prod:
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
