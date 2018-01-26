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




##################
#### POSTGRES ####


# TODO
# should remove db info from  app/config/config_test.yml
# and use sed or etcd or confd or both to update parameters.yml.dist or create parameters.yml
# sed -e s/'database_host: 127.0.0.1'/'database_host: ${DBHOST}'/g -i app/config/parameters.yml.dist
if [ -n "${DBHOST}" ]
then
    sed -e s/'127.0.0.1'/${DBHOST}/g -i app/config/config_test.yml

    #TODO
    # should use env var from etcd (for password)
    echo  ${DBHOST}:5432:*:${DBROOTUSER}:${DBROOTPASSWORD} >> $HOME/.pgpass
    chmod 600  $HOME/.pgpass
    cat  $HOME/.pgpass
fi


#################
#### ELASTIC ####
if [ -n "${ELHOST}" ]
then
    sed -e s/'elastic_search.hostname:  127.0.0.1'/"elastic_search.hostname:  ${ELHOST}"/g -i app/config/parameters.yml.dist
fi



echo "
imports:
    - { resource: config_dev.yml }

framework:
    test: ~
    profiler:
        collect: false

web_profiler:
    toolbar: false
    intercept_redirects: false

swiftmailer:
    disable_delivery: true

# Doctrine Configuration
doctrine:
    dbal:
        default_connection: default
        connections:
            default:
                driver:   pdo_pgsql
                host:     ${DBHOST}
                port:     5432
                dbname:   ${DBAPPNAME}
                user:     ${DBAPPUSER}
                password: ${DBAPPPASSWORD}
                charset:  UTF8
            session:
                driver:   pdo_pgsql
                host:     ${DBHOST}
                port:     5432
                dbname:   ${DBAPPNAME}
                user:     ${DBAPPUSER}
                password: ${DBAPPPASSWORD}
                charset:  UTF8


blast_search:
    elastic_search:
        hostname:  ${ELHOST}
        port: 9200
    global_index_alias: ${ELALIAS}


" > app/config/config_test.yml
