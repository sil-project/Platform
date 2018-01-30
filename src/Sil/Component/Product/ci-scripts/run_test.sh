#!/usr/bin/env sh


bin/phpunit --verbose --debug -c phpunit.xml.dist --coverage-clover build/logs/clover.xml

bin/codecept run --steps --html --xml --verbose --coverage-html
