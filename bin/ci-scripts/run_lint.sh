#!/usr/bin/env bash

#rm -f composer.lock

composer validate --no-check-lock

for i in src/*/Bundle/* src/*/Component/*
do
    cd $i
    pwd
    composer validate
    cd -
done
