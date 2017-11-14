#!/usr/bin/env sh
set -ev

# Database creation

###
### postgresql
###

# needed in .travis.yml
#services:
#  - postgresql
# or here :  sudo /etc/init.d/postgresql start

# (we try to create a travis user)
psql -c "CREATE USER sil_user WITH PASSWORD 'sil';" -U postgres
psql -c 'ALTER ROLE sil_user WITH CREATEDB;' -U postgres

psql -c 'CREATE DATABASE sil;' -U postgres
psql -c 'ALTER DATABASE sil OWNER TO sil_user' -U postgres


psql -c 'CREATE EXTENSION "uuid-ossp";' -U postgres -d sil

###
###
###
