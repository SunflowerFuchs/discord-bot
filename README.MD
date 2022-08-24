# Modular Discord Bot

This bot is not yet production ready, as it is still missing:

- separation into modules
- improved ease-of-use
- documentation

## This file is TODO

## Requirements

- [docker](https://docs.docker.com/get-started/)
- [discord developer application](https://discord.com/developers/applications)

## Quickstart

this quickstart will likely change once i release 1.0, since i WILL get rid of the way the testing currently works

- clone this repository
- cd into it
- copy `test/test.ini.example` to `test/test.ini`
- enter your bot token into the ini
- run `docker build ./test/docker/ --tag=discord-bot` to set up the docker container
- run `docker run --rm -v "$(pwd):/app" --workdir=/app discord-bot composer install` to install dependencies
- run `docker run --rm -v "$(pwd):/app" --workdir=/app discord-bot composer require gingteam/tiktok:dev-main` to install
  additional dependencies
- run `docker run --restart=unless-stopped -v "$(pwd):/app" --workdir=/app discord-bot php /app/test/test.php`