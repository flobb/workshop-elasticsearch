#!/bin/sh
set -e

# Change www-data's uid & guid to be the same as directory in host
sed -ie "s/`id -u www-data`:`id -g www-data`/`stat -c %u /srv`:`stat -c %g /srv`/g" /etc/passwd

exec docker-php-entrypoint "$@"
