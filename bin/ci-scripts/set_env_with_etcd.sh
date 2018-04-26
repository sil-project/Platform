#!/usr/bin/env bash
set -ex

export ETCDCTL_API=3

# rand number to avoid build colision (same db used by two build)
if [ ! -f shuf.nbr ]
then
    shuf -i 200-600 -n 1 > shuf.nbr
fi

#RND may contain branch with '-' or upper case char which may not work as database name for postgre
suffix=$(echo $RND|sed -e s/-/_/g|tr '[:upper:]' '[:lower:]')$(echo -n $(cat shuf.nbr ))
prefix="/platform/build/$suffix"

if [ -z "$ETCDHOST" ]
then
    ETCDHOST="etcd.host"
fi
ETCDENDPOINT="--endpoints=http://${ETCDHOST}:2379"

if [ -z "$ETCDCTLCMD" ]
then
    #ETCDCTLCMD="docker exec $ETCDHOST etcdctl "
    ETCDCTLCMD="etcdctl"
fi

# check
$ETCDCTLCMD get  --prefix '/default' $ETCDENDPOINT

# get postgres default
postgreshost=$($ETCDCTLCMD get /default/postgres/hostname --print-value-only $ETCDENDPOINT)
postgresuser=$($ETCDCTLCMD get /default/postgres/root/username --print-value-only $ETCDENDPOINT)
postgrespass=$($ETCDCTLCMD get /default/postgres/root/password --print-value-only $ETCDENDPOINT)
# TODO add a check default cnx with psql

# get elastic default
elastichost=$($ETCDCTLCMD get /default/elastic/hostname --print-value-only $ETCDENDPOINT)

# get selenium default
seleniumhost=$($ETCDCTLCMD get /default/selenium/hostname --print-value-only $ETCDENDPOINT)

# get ip
currentip=$(hostname -i) # works only if the host name can be resolved

# set postgres env
$ETCDCTLCMD put $prefix/postgres/hostname $postgreshost $ETCDENDPOINT
$ETCDCTLCMD put $prefix/postgres/root/username $postgresuser $ETCDENDPOINT
$ETCDCTLCMD put $prefix/postgres/root/password $postgrespass $ETCDENDPOINT

$ETCDCTLCMD put $prefix/postgres/user/dbname sil_db_$suffix $ETCDENDPOINT
$ETCDCTLCMD put $prefix/postgres/user/username sil_user_$suffix $ETCDENDPOINT
$ETCDCTLCMD put $prefix/postgres/user/password sil_password_$suffix $ETCDENDPOINT

# set elastic env
$ETCDCTLCMD put $prefix/elastic/hostname $elastichost $ETCDENDPOINT
$ETCDCTLCMD put $prefix/elastic/indexalias sil_$suffix $ETCDENDPOINT

# set selenium env
$ETCDCTLCMD put $prefix/selenium/hostname $seleniumhost $ETCDENDPOINT

# set symfony env
$ETCDCTLCMD put $prefix/symfony/env test $ETCDENDPOINT # maybe put this in env variable (or not)
$ETCDCTLCMD put $prefix/symfony/addr $currentip':8042' $ETCDENDPOINT

$ETCDCTLCMD get  --prefix $prefix $ETCDENDPOINT

confd -onetime -backend etcdv3 -node http://${ETCDHOST}:2379 -confdir ./etc/confd -log-level debug -prefix $prefix
