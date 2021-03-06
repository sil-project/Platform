#!/usr/bin/env bash
set -ex

mkdir --parents "${HOME}/bin"

if [ "${WHORUN}" = travis  ]
then
    # Ugly hack
    echo "memory_limit=-1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/travis.ini

    # for selenium
    sudo apt-get install xvfb
    sudo apt-get install chromium-browser
    sudo apt-get install firefox
    #sudo apt-get install chromium-chromedriver

    curl -O https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-6.1.2.deb
    sudo dpkg -i --force-confnew  elasticsearch-6.1.2.deb
    sudo service elasticsearch restart
fi


if [ ! -x ${HOME}/bin/coveralls ]
then
    # Coveralls client install
    wget -q https://github.com/satooshi/php-coveralls/releases/download/v1.0.1/coveralls.phar --output-document="${HOME}/bin/coveralls"
    chmod u+x "${HOME}/bin/coveralls"
fi
