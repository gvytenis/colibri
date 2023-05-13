# Colibri

Colibri is a library management software, using MySQL as the database.
It provides an easy-to-use interface for managing books, authors, categories, users and reservations.
With Colibri, you can quickly search for and view book information, manage borrowers, and track borrowing history.

## Stack

This web app uses a Docker stack with Traefik as a reverse proxy and load balancer, Nginx as the web server,
PHP for the backend, MySQL as the database, and Node.js for the frontend. MySQL data is persisted in Docker volume.

## Prerequisites

Make sure you have the following installed:
- [Docker](https://www.docker.com/products/docker-desktop/)
- Make (optional)

## Usage

### Makefile

- Run `make help` to see available commands
- Run `make start` to start the stack
- Run `make stop` to stop the stack
- Run `make exec-php` to enter PHP container
- Run `make exec-node` to enter Node.js container

### Docker

- Run `docker-compose up -d` to start the stack
- Run `docker-compose stop` to stop the stack
- Run `docker-compose exec php sh` to enter PHP container
- Run `docker-compose exec node sh` to enter Node.js container

**Note:** once in either container, run `make` to see available helper commands.

## Accessing the application

Once the Docker stack is running, you can access the web app at the following URLs:

Backend: [http://colibri.backend.localhost](http://colibri.backend.localhost) or [http://localhost:8088](http://localhost:8088)
Frontend: [http://colibri.frontend.localhost](http://colibri.frontend.localhost) or [http://localhost:8000](http://localhost:8000)

Entering a container
You can enter a container by running make exec. By default, this will enter the PHP container. You can specify a different container by passing the container name as a parameter, like so: make exec container=node.

Stopping the Docker stack
To stop the Docker stack, run make stop.

## Configuration

The docker-compose.yml file defines the services used by the Docker stack.
The .env file defines the environment variables used by these services.

## Useful links

| Service           | Link                                                                   |
|-------------------|------------------------------------------------------------------------|
| Traefik Dashboard | [http://colibri.traefik.localhost](http://colibri.traefik.localhost)   |
| Backend           | [http://colibri.backend.localhost](http://colibri.backend.localhost)   |
| Frontend          | [http://colibri.frontend.localhost](http://colibri.frontend.localhost) |
