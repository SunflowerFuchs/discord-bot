FROM php:8.1-cli

RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*

ARG TZ="Europe/Berlin"
ENV TZ ${TZ}

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions zip xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer