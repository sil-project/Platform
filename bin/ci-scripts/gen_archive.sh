#!/usr/bin/env bash
set -ex

Name=Platform
# git symbolic-ref -q --short HEAD || git describe --tags --exact-match
#Version=$(git describe --tags)
#Tag=$(git describe --tags --abbrev=0)
Branch=$(git name-rev  --name-only $(git rev-parse HEAD) | sed -e s/\\^.*//g | cut -f-1 -d'/')

# Clean current git dir
git clean -df
git checkout -- .

#Filename=${Name}_${Version}.tar.gz
Filename=${Name}_${Branch}.tar.gz
#echo ${Version} > Version.txt
#echo ${Tag} > Tag.txt
echo ${Branch} > Branch.txt

rm -f ${Filename}

# gen archive --transform='s|\./|./'${Tag}'/|g'
tar -czf ${Filename} ./*

sha256sum ${Filename} > ${Filename}.sha256.txt
