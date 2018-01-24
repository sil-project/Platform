#!/usr/bin/env bash
set -v

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


# Database creation

###
### mysql
###

# (mysql service is started by default by travis for each build instance
# (mysql travis user is created by travis for each build instance
# mysql -u travis -e 'CREATE DATABASE travis;' -v

###
### postgresql
###

#psql auto password
#echo  localhost:5432:*:postgres:postgres24 >> ~/.pgpass

# needed in .travis.yml
#services:
#  - postgresql
# or here :  sudo /etc/init.d/postgresql start


psql -w -h ${DBHOST} -c "DROP DATABASE IF EXISTS test_sil_db;" -U postgres
psql -w -h ${DBHOST} -c "DROP ROLE IF EXISTS test_sil_user;" -U postgres


# (we try to create a travis user)
psql -w -h ${DBHOST} -c "CREATE USER test_sil_user WITH PASSWORD 'test_sil_password';" -U postgres
psql -w -h ${DBHOST} -c 'ALTER ROLE test_sil_user WITH CREATEDB;' -U postgres

psql -w -h ${DBHOST} -c 'CREATE DATABASE test_sil_db;' -U postgres
psql -w -h ${DBHOST} -c 'ALTER DATABASE test_sil_db OWNER TO test_sil_user' -U postgres


psql -w -h ${DBHOST} -c 'CREATE EXTENSION "uuid-ossp";' -U postgres -d test_sil_db

# create it for bundle phpunit test
# travis user already exist on travis
#psql -w -h ${DBHOST} -c 'CREATE DATABASE travis;' -U postgres
#psql -w -h ${DBHOST} -c 'ALTER DATABASE travis OWNER TO travis' -U postgres

#psql -w -h ${DBHOST} -c 'CREATE EXTENSION "uuid-ossp";' -U postgres -d travis

###
###
###
