#!/usr/bin/env sh

vendor/phpunit/phpunit/phpunit -v -c phpunit.xml.dist --coverage-clover build/logs/clover.xml

bin/ci-scripts/do_it_for_bundle.sh run test
