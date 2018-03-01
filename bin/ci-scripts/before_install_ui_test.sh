#!/usr/bin/env bash
set -ex

#if [ ! -x ${HOME}/bin/chromedriver ]
#then
#    #wget -q http://chromedriver.storage.googleapis.com/2.12/chromedriver_linux64.zip
#    #wget -q http://chromedriver.storage.googleapis.com/2.29/chromedriver_linux64.zip
#    wget -q http://chromedriver.storage.googleapis.com/2.33/chromedriver_linux64.zip
#    unzip -o chromedriver_linux64.zip
#    mv chromedriver ${HOME}/bin/
#    #ln -s /usr/lib/chromium-browser/chromedriver ${HOME}/bin/chromedriver
#fi

if [ -z $GECKOVERSION ]
then
    GECKOVERSION=v0.19.1
fi

if [ ! -x ${HOME}/bin/geckodriver ]
then
    wget -q https://github.com/mozilla/geckodriver/releases/download/${GECKOVERSION}/geckodriver-${GECKOVERSION}-linux64.tar.gz
    gunzip -f geckodriver-${GECKOVERSION}-linux64.tar.gz
    tar -xvf geckodriver-${GECKOVERSION}-linux64.tar
    mv geckodriver ${HOME}/bin/
    geckodriver --version
fi

if [ -z ${SELENIUMVERSION} ]
then
    SELENIUMVERSION=3.7
fi

# TODO check if java is installed
if [ ! -e ${HOME}/bin/selenium-server-standalone.jar ]
then
    wget -q https://selenium-release.storage.googleapis.com/${SELENIUMVERSION}/selenium-server-standalone-${SELENIUMVERSION}.0.jar --output-document=${HOME}/bin/selenium-server-standalone.jar
fi

# install nvm
#rm -rf $HOME/.nvm
#rm -rf $HOME/.npm
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.8/install.sh | bash
export NVM_DIR="$HOME/.nvm"

set +x
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" # This loads nvm
set -x

# install node 4.2.6
nvm install 8.9

nvm --version
npm -v
