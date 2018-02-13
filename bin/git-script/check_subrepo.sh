#!/usr/bin/env bash
set -e

# Bundle & Component

for i in src/*/Bundle/*/ src/*/Component/*/
do
    if [ ! -f $i/.gitrepo ]
    then
        echo "Subrepo missing for: "$i
    fi

done
