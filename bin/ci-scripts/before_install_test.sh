#!/usr/bin/env sh
set -ev

mkdir --parents "${HOME}/bin"

if [ $# -eq 0 ]
then
    # Ugly hack
    echo "memory_limit=-1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/travis.ini
fi
   
composer self-update --stable
composer clear-cache


# check version

composer -V

