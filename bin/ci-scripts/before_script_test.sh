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


set +e
curl -s -X GET "http://$SERVERADDR" > /dev/null 
if [ $? -eq 0 ]
then
    bin/console server:stop --no-interaction --env=$SERVERENV
fi

# start server as prod for travis timeout on dev...
# bin/console server:stop
bin/console cache:clear --no-interaction --env=$SERVERENV
bin/console server:start --no-interaction $SERVERADDR --env=$SERVERENV

