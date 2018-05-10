SHELL=/bin/bash
.PHONY: help check fix test build publish

help: ## Show this help
	@echo "Targets:"
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/\(.*\):.*##[ \t]*/    \1 ## /' | sort | column -t -s '##'

check: ## Check the source files with code sniffer
	./vendor/bin/phpcs --colors

fix: ## Fix the problems found by code sniffer
	./vendor/bin/phpcbf

test: ## Run unit tests
	./vendor/bin/phpunit --colors=always

build: ## Build the phar file from source code
	./vendor/bin/box build && chmod u+x bin/clapi.phar
	gpg -u negabor@gmail.com --detach-sign --output ./bin/clapi.phar.asc ./bin/clapi.phar
