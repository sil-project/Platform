#!/usr/bin/env bash
set -v

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


psql -w -c "DROP DATABASE IF EXISTS test_sil_db;" -U postgres
psql -w -c "DROP ROLE IF EXISTS test_sil_user;" -U postgres


# (we try to create a travis user)
psql -w -c "CREATE USER test_sil_user WITH PASSWORD 'test_sil_password';" -U postgres
psql -w -c 'ALTER ROLE test_sil_user WITH CREATEDB;' -U postgres

psql -w -c 'CREATE DATABASE test_sil_db;' -U postgres
psql -w -c 'ALTER DATABASE test_sil_db OWNER TO test_sil_user' -U postgres


psql -w -c 'CREATE EXTENSION "uuid-ossp";' -U postgres -d test_sil_db

# create it for bundle phpunit test
# travis user already exist on travis
#psql -w -c 'CREATE DATABASE travis;' -U postgres
#psql -w -c 'ALTER DATABASE travis OWNER TO travis' -U postgres

#psql -w -c 'CREATE EXTENSION "uuid-ossp";' -U postgres -d travis

###
###
###
