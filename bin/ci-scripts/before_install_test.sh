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

# for selenium
sudo apt-get install xvfb
sudo apt-get install chromium-browser
sudo apt-get install firefox
#sudo apt-get install chromium-chromedriver

#wget http://chromedriver.storage.googleapis.com/2.12/chromedriver_linux64.zip
#wget http://chromedriver.storage.googleapis.com/2.29/chromedriver_linux64.zip
wget http://chromedriver.storage.googleapis.com/2.33/chromedriver_linux64.zip
unzip chromedriver_linux64.zip
mv chromedriver ${HOME}/bin/
#ln -s /usr/lib/chromium-browser/chromedriver ${HOME}/bin/chromedriver

wget https://github.com/mozilla/geckodriver/releases/download/v0.19.1/geckodriver-v0.19.1-linux64.tar.gz
gunzip geckodriver-v0.19.1-linux64.tar.gz
tar -xvf geckodriver-v0.19.1-linux64.tar
mv geckodriver ${HOME}/bin/

# selenium not need as we use a composer package for this
#wget https://selenium-release.storage.googleapis.com/3.4/selenium-server-standalone-3.4.0.jar
#java -jar selenium-server-standalone-3.4.0.jar &
wget https://selenium-release.storage.googleapis.com/3.7/selenium-server-standalone-3.7.0.jar
mv selenium-server-standalone-3.7.0.jar  ${HOME}/bin/selenium-server-standalone.jar


# codeception not need as we use a composer package for this
#wget "http://codeception.com/codecept.phar"  --output-document="${HOME}/bin/codecept"
#chmod u+x "${HOME}/bin/codecept"



# install nvm
rm -rf $HOME/.nvm
rm -rf $HOME/.npm
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.2/install.sh | bash
export NVM_DIR="$HOME/.nvm"

set +v
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" # This loads nvm
set -v

# install node 4.2.6
nvm install 8.9




# check version

composer -V

chromium-browser --version
chromedriver --version
geckodriver --version

nvm --version
npm -v

# check java version
java -version
