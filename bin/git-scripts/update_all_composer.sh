#!/usr/bin/env bash

set -ex


# for i in src/*/Component/*/ src/*/Bundle/*/
#do
#  cd $i
#  pwd
##  git checkout composer.json
#  cd -
#done



for i in src/*/Component/*/
do
    cd $i
    pwd
    composer validate
    composer config version dev-wip-platform
    composer config license LGPL-3.0-only
    composer require --dev "phpunit/phpunit" "^6.4"  --no-scripts --no-update
    composer config bin-dir bin
    composer validate
    git add composer.json

    cd -
done


for i in src/*/Bundle/*/
do
    cd $i
    pwd
    composer validate
    composer config version dev-wip-platform
    composer config license LGPL-3.0-only
    composer require "symfony/symfony" "^3.4" --no-scripts --no-update
    composer require --dev "symfony/phpunit-bridge" "^3.4" --no-scripts --no-update
    composer require --dev "phpunit/phpunit" "^6.4"  --no-scripts --no-update
    composer config bin-dir bin
    composer validate
    git add composer.json

    cd -
done
