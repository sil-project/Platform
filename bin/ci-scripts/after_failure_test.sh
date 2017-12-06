#!/usr/bin/env sh

source source_env.sh

sudo apt-get install lynx

for i in $CODECEPT_OUPUT/*.html
do
    echo '========================================'
    echo $i
    echo '========================================'
    lynx -dump $i
    echo '========================================'
done
