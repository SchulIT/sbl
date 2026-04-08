#!/bin/sh

# Clear cache
/usr/local/bin/frankenphp php-cli bin/console cache:clear

# Setup app
/usr/local/bin/frankenphp php-cli bin/console app:setup

# Migrate database
/usr/local/bin/frankenphp php-cli bin/console doctrine:migrations:migrate --no-interaction -v

# Start FrankenPHP
/usr/local/bin/frankenphp run --config /etc/caddy/Caddyfile
