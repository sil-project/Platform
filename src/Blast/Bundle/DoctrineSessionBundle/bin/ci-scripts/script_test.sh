#!/usr/bin/env bash
set -ev
which composer
composer --version

which phpunit
phpunit --version


# grr composer push own dir in $path (so it do not use our version of phpunit)
# grr and travis phpunit version does not work on php 5.6 (it is too old)
#    composer test-mysql
#    composer test-postgresql

# test mysql
cp tests/Resources/App/config/config_test_mysql.yml tests/Resources/App/config/config_test.yml
phpunit

# test postgresql 
cp tests/Resources/App/config/config_test_postgresql.yml tests/Resources/App/config/config_test.yml
phpunit


#cleaning
cp tests/Resources/App/config/config_test_mysql.yml tests/Resources/App/config/config_test.yml

