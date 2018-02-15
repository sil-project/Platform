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
#bin/console lisem:install:setup --with-samples --yes --env=$SERVERENV
#bin/console sylius:install:setup --no-interaction --env=$SERVERENV
bin/console sylius:fixtures:load ecommerce_requirements --no-interaction --env=$SERVERENV
bin/console sil:user:fixture --no-interaction --env=$SERVERENV