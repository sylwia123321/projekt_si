# Docker Symfony Starter Kit

Welcome to the Docker Symfony Starter Kit! This starter kit is based on The perfect kit starter for a Symfony 4 project with Docker and PHP 7.2.

## What's Inside?

- **Apache 2.4.57 (Debian)**
- **PHP 8.3 FPM**
- **MySQL 8.3.1**
- **NodeJS LTS (latest)**
- **Composer**
- **Symfony CLI**
- **xdebug**
- **djfarrelly/maildev**

## Requirements

To use this starter kit, you need to have Docker and Docker Compose installed on your machine.

## Installation

1. (Optional) Add `127.0.0.1   symfony.local` in your host file.
2. Run `build-env.sh` (or `build-env.ps1` on Windows).
3. Enter the PHP container:
   
docker-compose exec php bash


4. To install Symfony LTS inside the container, execute:

cd app
rm .gitkeep
git config --global user.email "you@example.com"
symfony new ../app --version=lts --webapp
chown -R dev.dev *


## Container URLs and Ports

- **Project URL**: [http://localhost:8000](http://localhost:8000) or [http://symfony.local:8000](http://symfony.local:8000)
- **MySQL Inside Container**: host is `mysql`, port: `3306`
- **MySQL Outside Container**: host is `localhost`, port: `3307`
- **Passwords, DB name** are in `docker-compose.yml`
- **djfarrelly/maildev** is available from the browser on port `8001`
- **xdebug** is available remotely on port `9000`

## Database Connection in Symfony `.env` File

DATABASE_URL=mysql://symfony
@mysql:3306/symfony?serverVersion=5.7


## Useful Commands

- `docker-compose up -d`: start containers
- `docker-compose down`: stop containers
- `docker-compose exec php bash`: enter into PHP container
- `docker-compose exec mysql bash`: enter into MySQL container
- `docker-compose exec apache bash`: enter into Apache2 container

## Troubleshooting

If you encounter an error like `ERROR: for apache 'ContainerConfig'` after `docker-compose up -d`, you can solve it with:

docker-compose up -d --force-recreate

---

Feel free to customize and expand this README with more details about your project as needed. Good luck with your Docker Symfony Starter Kit!
