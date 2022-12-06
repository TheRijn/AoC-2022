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

.PHONY: prep_day
prep_day:
	ifndef DAY
	$(error DAY is not set)
	endif
	mkdir input/$(DAY)
	touch input/$(DAY)/example.input input/$(DAY)/input input/$(DAY)/one.output input/$(DAY)/two.output
	@echo "<?php\n\n\
declare(strict_types=1);\n\n\
namespace App\Command;\n\n\
use Ds\Vector;\n\
use Symfony\Component\Console\Attribute\AsCommand;\n\
use Symfony\Component\Console\Output\OutputInterface;\n\n\
#[AsCommand(name: 'aoc:$(DAY)')]\n\
class Day$(DAY) extends AocCommand\n\
{\n\
    /** @param Vector \$$input */\n\
    protected function partOne(Vector \$$input, OutputInterface \$$output): void\n\
    {\n\
    }\n\n\
    /** @param Vector \$$input */\n\
    protected function partTwo(Vector \$$input, OutputInterface \$$output): void\n\
    {\n\
    }\n\
}" > src/Command/Day$(DAY).php
	@echo "<?php\n\n\
declare(strict_types=1);\n\
namespace App\Tests\Unit;\n\n\
class Day$(DAY)Test extends AocBase\n\
{\n\
   protected const DAY = $(DAY);\n\
}" > tests/Unit/Day$(DAY)Test.php