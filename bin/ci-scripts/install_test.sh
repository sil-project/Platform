#!/usr/bin/env sh
set -ev

rm -rf composer.lock # vendor

# composer update --no-interaction --prefer-dist
composer install --no-interaction --prefer-dist
#composer require --no-interaction --dev phpunit/phpunit
#composer require --no-interaction --dev codeception/codeception
#composer require --no-interaction --dev se/selenium-server-standalone

bin/ci-scripts/do_it_for_bundle.sh install test
