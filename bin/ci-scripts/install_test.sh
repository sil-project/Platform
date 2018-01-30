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


#rm -rf composer.lock # vendor

set +e
composer validate
CMPCMD=install --prefer-dist
if [ $? -ne 0 ]
then
    CMPCMD=update
fi

set -e
# composer update --no-interaction --prefer-dist
composer ${CMPCMD} --no-interaction

if [ -n "${ENABLE_UI}" ]
then
    export NVM_DIR="$HOME/.nvm"
    set +v
    [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" # This loads nvm
    set -v

    npm install
    npm run gulp
fi

# database creation
bin/console doctrine:schema:drop --force --no-interaction  --full-database --env=$SERVERENV   # --full-database drops default + session connections
bin/console doctrine:database:create --if-not-exists --no-interaction --env=$SERVERENV
bin/console doctrine:schema:create --no-interaction --em=default --env=$SERVERENV
bin/console doctrine:schema:create --no-interaction --em=session --env=$SERVERENV
#bin/console doctrine:schema:update --force --no-interaction --env=$SERVERENV
#bin/console doctrine:schema:validate --no-interaction --env=$SERVERENV

bin/console fos:elastica:reset --no-interaction --env=$SERVERENV
bin/console fos:elastica:populate --no-interaction --env=$SERVERENV

# Not needed as it is launch in composer install
#bin/console blast:patchs:apply --no-interaction --env=$SERVERENV

# asset and data

# TODO move this to another script
#bin/console lisem:install:setup --with-samples --yes --env=$SERVERENV
#bin/console sylius:install:setup --no-interaction --env=$SERVERENV
bin/console sylius:fixtures:load ecommerce_requirements --no-interaction --env=$SERVERENV
bin/console sil:user:fixture --no-interaction --env=$SERVERENV

if [ -n "${ENABLE_UI}" ]
then
    bin/console assets:install --no-interaction --env=$SERVERENV
    bin/console sylius:theme:assets:install  --no-interaction --env=$SERVERENV # must be done after assets:install
fi


#bin/ci-scripts/do_it_for_bundle.sh install test
