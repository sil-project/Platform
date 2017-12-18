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

# needed in .travis.yml
#services:
#  - postgresql
# or here :  sudo /etc/init.d/postgresql start

# (we try to create a travis user)
psql -c "CREATE USER test_sil_user WITH PASSWORD 'test_sil_password';" -U postgres
psql -c 'ALTER ROLE test_sil_user WITH CREATEDB;' -U postgres

psql -c 'CREATE DATABASE test_sil_db;' -U postgres
psql -c 'ALTER DATABASE test_sil_db OWNER TO test_sil_user' -U postgres


psql -c 'CREATE EXTENSION "uuid-ossp";' -U postgres -d test_sil_db

# create it for bundle phpunit test
# travis user already exist on travis
#psql -c 'CREATE DATABASE travis;' -U postgres
#psql -c 'ALTER DATABASE travis OWNER TO travis' -U postgres

#psql -c 'CREATE EXTENSION "uuid-ossp";' -U postgres -d travis

###
###
###
