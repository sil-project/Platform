#!/usr/bin/env sh

phpunit -v -c phpunit.xml.dist --coverage-clover build/logs/clover.xml
