FROM php:7.4-cli

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions zip xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer