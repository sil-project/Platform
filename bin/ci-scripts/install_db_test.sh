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

# database creation
#bin/console doctrine:schema:drop --force --no-interaction  --full-database --env=$SERVERENV   # --full-database drops default + session connections
bin/console doctrine:database:create --if-not-exists --no-interaction --env=$SERVERENV

for i in default session
do
    # bin/console doctrine:schema:create --no-interaction --em=$i --env=$SERVERENV
    bin/console doctrine:schema:update --no-interaction --em=$i --env=$SERVERENV --force
    # validate does not work on travis and jenkins for some obscur cause
    #bin/console doctrine:schema:validate --no-interaction --em=$i --env=$SERVERENV
done

# as search as been disabled
#bin/console fos:elastica:reset --no-interaction --env=$SERVERENV
#bin/console fos:elastica:populate --no-interaction --env=$SERVERENV

# Not needed as it is launch in composer install
#bin/console blast:patchs:apply --no-interaction --env=$SERVERENV

# TODO remove this when sil:user:fixture is re-usable
psql -w -h ${DBHOST} -c "INSERT INTO sil_user(id, username, password, email, enabled) VALUES ((SELECT uuid_generate_v4()), 'sil@sil.eu', 'sil', 'sil@sil.eu', true);" -U ${DBAPPUSER} -d ${DBAPPNAME}
