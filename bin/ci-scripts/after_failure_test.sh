#!/usr/bin/env sh

sudo apt-get install lynx
for i in src/Tests/_output/*.html
do
    echo '========================================'
    echo $i
    echo '========================================'
    lynx -dump $i
    echo '========================================'
done
