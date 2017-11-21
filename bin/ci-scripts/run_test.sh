#!/usr/bin/env sh

vendor/phpunit/phpunit/phpunit -v -c phpunit.xml.dist
#--coverage-clover build/logs/clover.xml

vendor/bin/codecept run Lisem -d --steps --fail-fast --no-interaction --no-exit --env=firefox -g login


#bin/ci-scripts/do_it_for_bundle.sh run test
