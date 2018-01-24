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


if [ -z "${DBHOST}" ]
then
    echo "Please add DBHOST in .env file as it is mandatory"
    exit 42
fi


# TODO
# should remove db info from  app/config/config_test.yml
# and use sed or etcd or confd or both to update parameters.yml.dist
# sed -e s/'database_host: 127.0.0.1'/'database_host: ${DBHOST}'/g -i app/config/parameters.yml.dist


sed -e s/'127.0.0.1'/'${DBHOST}'/g -i app/config/config_test.yml


#TODO
# should use env var from etcd (for password)
echo  ${DBHOST}:5432:*:postgres:postgres24 >> ~/.pgpass
chmod 600  ~/.pgpass
