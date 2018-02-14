#!/usr/bin/env bash
set -ev


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



if [ -n "${ENABLE_UI}" ]
then
    export NVM_DIR="$HOME/.nvm"
    set +v
    [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" # This loads nvm
    set -v

    npm install
    npm run gulp

    bin/console assets:install --no-interaction --env=$SERVERENV
    bin/console sylius:theme:assets:install  --no-interaction --env=$SERVERENV # must be done after assets:install
fi


#bin/ci-scripts/do_it_for_bundle.sh install test
