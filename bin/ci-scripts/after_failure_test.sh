#!/usr/bin/env sh

sudo apt-get install lynx
for i in tests/_output/*.html
do
    echo '========================================'
    echo $i
    echo '========================================'
    lynx -dump $i
    echo '========================================'
done

