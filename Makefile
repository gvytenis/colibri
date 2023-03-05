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

start: ##@Host Start Docker stack
	docker-compose up -d

stop: ##@Host Stop Docker stack
	docker-compose stop

exec: ##@Host Enter container (add container=CONTAINER_NAME to enter specific container, default is PHP)
ifeq ($(strip $(container)),)
	docker-compose exec --user=app php zsh
else
	docker-compose exec $(container) sh
endif
