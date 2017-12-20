#!/usr/bin/env bash
set -e


if [ $# -ne 2 ]
then
    echo 'Need Param $script $target'
    exit 0
fi

for i in src/*/Bundle/*
do    
    cd $i
    pwd
    if [ -x ./bin/ci-scripts/${1}_${2}.sh ]
       then 
           ./bin/ci-scripts/${1}_${2}.sh
    fi
    cd -
done


