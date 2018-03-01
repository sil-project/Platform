#!/usr/bin/env bash
set -ex


# TODO share this between script (in an include)
if [ -f .env ]
then
    source .env
else
    echo "Please run this script from project root, and check .env file as it is mandatory"
    echo "If it is missing a quick solution is :"
    echo "ln -s .env.travis .env"
    exit 42
fi


#rm -rf composer.lock # vendor

set +e
composer validate
CMPCMD='install --prefer-dist'
if [ $? -ne 0 ]
then
    CMPCMD=update
fi

set -e
# composer update --no-interaction --prefer-dist
composer ${CMPCMD} ${COMPOSERARG} --no-ansi --no-interaction
