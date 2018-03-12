#!/usr/bin/env bash
set -ex

export ETCDCTL_API=3

# rand number to avoid build colision (same db used by two build)
if [ ! -f shuf.nbr ]
then
    shuf -i 200-600 -n 1 > shuf.nbr
fi

#RND may contain branch with '-' or upper case char which may not work as database name for postgre
prefix=$(echo $RND|sed -e s/-/_/g|tr '[:upper:]' '[:lower:]')$(echo -n $(cat shuf.nbr ))

if [ -z "$ETCDHOST" ]
then
    ETCDHOST="etcd.host"
fi
ETCDENDPOINT="--endpoints='http://${ETCDHOST}:2379'"

if [ -z "$ETCDCTLCMD" ]
then
    #ETCDCTLCMD="docker exec $ETCDHOST etcdctl "
    ETCDCTLCMD="etcdctl"
fi

# check
$ETCDCTLCMD get  --prefix '/default'

# get postgres default
postgreshost=$($ETCDCTLCMD get /default/postgres/hostname --print-value-only $ETCDENDPOINT)
postgresuser=$($ETCDCTLCMD get /default/postgres/root/username --print-value-only $ETCDENDPOINT)
postgrespass=$($ETCDCTLCMD get /default/postgres/root/password --print-value-only $ETCDENDPOINT)
# TODO add a check default cnx with psql

# get elastic default
elastichost=$($ETCDCTLCMD get /default/elastic/hostname --print-value-only $ETCDENDPOINT)


# set postgres env
$ETCDCTLCMD put /build/$prefix/postgres/hostname $postgreshost $ETCDENDPOINT
$ETCDCTLCMD put /build/$prefix/postgres/root/username $postgresuser $ETCDENDPOINT
$ETCDCTLCMD put /build/$prefix/postgres/root/password $postgrespass $ETCDENDPOINT

$ETCDCTLCMD put /build/$prefix/postgres/user/dbname sil_db_$prefix $ETCDENDPOINT
$ETCDCTLCMD put /build/$prefix/postgres/user/username sil_user_$prefix $ETCDENDPOINT
$ETCDCTLCMD put /build/$prefix/postgres/user/password sil_password_$prefix $ETCDENDPOINT

# set elastic env
$ETCDCTLCMD put /build/$prefix/elastic/hostname $elastichost $ETCDENDPOINT
$ETCDCTLCMD put /build/$prefix/elastic/indexalias sil_$prefix $ETCDENDPOINT

# set symfony env
$ETCDCTLCMD put /build/$prefix/symfony/env test $ETCDENDPOINT # maybe put this in env variable (or not)
$ETCDCTLCMD put /build/$prefix/symfony/addr '127.0.0.1:8042' $ETCDENDPOINT



$ETCDCTLCMD get  --prefix /build/$prefix $ETCDENDPOINT
