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


# sed -e s/'database_host: 127.0.0.1'/'database_host: ${DBHOST}'/g -i app/config/parameters.yml.dist
if [ -n "${DBHOST}" ]
then
    echo  ${DBHOST}:5432:*:${DBROOTUSER}:${DBROOTPASSWORD} >> $HOME/.pgpass
    chmod 600  $HOME/.pgpass
    cat  $HOME/.pgpass
fi
