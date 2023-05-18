## Configuration

The docker-compose.yml file defines the services used by the Docker stack.
The `.env` file defines the environment variables used by these services.

## Upgrading versions

Each part of the stack can be easily upgraded by changing environment variables in the global
`.env` file which is placed in the root directory.

```
## Infrastructure configuration
PHP_VERSION=8.2.3
NGINX_VERSION=1.23.3
MYSQL_VERSION=8.0
NODE_VERSION=19.7
TRAEFIK_VERSION=2.5.1
STRUCTURIZR_VERSION=3067

COMPOSER_VERSION=2.5.1
APCU_VERSION=5.1.22
```

