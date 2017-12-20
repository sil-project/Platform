#!/usr/bin/env bash
set -ev

#composer update --prefer-dist --no-interaction
composer global require sllh/composer-lint:@stable --prefer-dist --no-interaction

#gem install yaml-lint

#bin/ci-scripts/do_it_for_bundle.sh install lint
