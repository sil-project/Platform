#!/usr/bin/env bash
set -ev

name=./${1}_${TARGET}.sh
if [ -x ${name} ]
then
    ${name} 
fi
