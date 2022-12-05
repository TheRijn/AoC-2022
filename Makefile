# Self-Documented Makefile see https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html

.DEFAULT_GOAL := help

.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY: install
install: ## install dependencies
	composer install

.PHONY: test
test: ## Run all the unit-test
	@bin/phpunit

.PHONY: d_install
d_install: ## Install dependencies in docker
	@echo '"composer install" on container app'
	@docker compose run --rm app composer install

.PHONY: shell
shell: ## Open an interactive shell
	@docker compose run --rm app ash
