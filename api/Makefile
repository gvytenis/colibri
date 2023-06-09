GREEN  := $(shell tput -Txterm setaf 2)
WHITE  := $(shell tput -Txterm setaf 7)
YELLOW := $(shell tput -Txterm setaf 3)
RESET  := $(shell tput -Txterm sgr0)

HELP_FUN = \
    %help; \
    while(<>) { \
    	push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ \
    }; \
    print "Usage: make [target]\n\n"; \
    for (sort keys %help) { \
		print "${WHITE}$$_:${RESET}\n"; \
		for (@{$$help{$$_}}) { \
			$$sep = " " x (32 - length $$_->[0]); \
			print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; \
		}; \
		print "\n"; \
    }

help: ##@Miscellaneous Show Makefile help
	@perl -e '$(HELP_FUN)' $(MAKEFILE_LIST)

test: ##@Tests Run PHPUnit (add filter=TestName to run specific test)
ifeq ($(strip $(filter)),)
	vendor/bin/phpunit --testsuite=all
else
	vendor/bin/phpunit --filter=$(filter)
endif

lint: ##@Code-Quality Run linters & static analysis
	vendor/bin/phpstan --ansi --memory-limit=1G
	vendor/bin/ecs --ansi --fix
	yamllint .
	bin/console lint:yaml config src
	bin/console lint:container
