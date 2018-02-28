#!/usr/bin/env bash
set -x

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

if [ -z "${CHANNELURL}" ]
then
    echo "Please add CHANNELURL in .env file as it is mandatory"
    exit 42
fi

psql -w -h ${DBHOST} -c  "update sil_ecommerce_channel set hostname='${CHANNELURL}';" -U ${DBROOTUSER} -d ${DBAPPNAME}
