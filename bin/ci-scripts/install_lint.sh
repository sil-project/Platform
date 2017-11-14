#!/usr/bin/env sh
set -ev

#composer update --prefer-dist --no-interaction
composer global require sllh/composer-lint:@stable --prefer-dist --no-interaction

#gem install yaml-lint
