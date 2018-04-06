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


export NVM_DIR="$HOME/.nvm"
set +x
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" # This loads nvm
set -x

npm install
npm run gulp

bin/console assets:install --no-interaction --env=$SERVERENV
#bin/console sylius:theme:assets:install  --no-interaction --env=$SERVERENV # must be done after assets:install
