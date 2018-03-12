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




##################
#### POSTGRES ####



# sed -e s/'database_host: 127.0.0.1'/'database_host: ${DBHOST}'/g -i app/config/parameters.yml.dist
if [ -n "${DBHOST}" ]
then
    # sed -e s/'127.0.0.1'/${DBHOST}/g -i app/config/config_test.yml
    sed -e s/'database_host: 127.0.0.1'/"database_host: ${DBHOST}"/g -i app/config/parameters.yml.dist
    #sed -e s/'database_port: 5432'/"database_port: 5432"/g -i app/config/parameters.yml.dist
    sed -e s/'database_name: sil'/"database_name: ${DBAPPNAME}"/g -i app/config/parameters.yml.dist
    sed -e s/'database_user: sil_user'/"database_user: ${DBAPPUSER}"/g -i app/config/parameters.yml.dist
    sed -e s/'database_password: sil'/"database_password: ${DBAPPPASSWORD}"/g -i app/config/parameters.yml.dist
fi


#################
#### ELASTIC ####
if [ -n "${ELHOST}" ]
then
    sed -e s/'elastic_search.hostname:  127.0.0.1'/"elastic_search.hostname:  ${ELHOST}"/g -i app/config/parameters.yml.dist
    sed -e s/'blast_search.global_index_alias: sil'/"blast_search.global_index_alias: ${ELALIAS}"/g -i app/config/parameters.yml.dist
fi

# DEBUG
cat app/config/parameters.yml.dist
