#!/usr/bin/env sh
set -ev

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

# (we try to create a travis user)
psql -c "CREATE USER blast_session_user WITH PASSWORD 'blast_session';" -U postgres
psql -c 'ALTER ROLE blast_session_user WITH CREATEDB;' -U postgres

psql -c 'CREATE DATABASE blast_session;' -U postgres
psql -c 'ALTER DATABASE blast_session OWNER TO blast_session_user' -U postgres


psql -c 'CREATE EXTENSION "uuid-ossp";' -U postgres -d blast_session




###
###
###
