#!/usr/bin/env bash
set -ev

for name in $@
do
    script_name=$(dirname $0)/$name
    if [ -x $script_name ]
    then
        $script_name
    fi
done
