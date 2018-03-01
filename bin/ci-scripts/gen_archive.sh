#!/usr/bin/env bash
set -ex

Name=Platform
# git symbolic-ref -q --short HEAD || git describe --tags --exact-match
#Version=$(git describe --tags)
#Tag=$(git describe --tags --abbrev=0)
Branch=$(git name-rev  --name-only $(git rev-parse HEAD) | sed -e s/\\^.*//g | awk -F'/' '{print $(NF)}')

# Clean current git dir
git clean -df
git checkout -- .

#Filename=${Name}_${Version}.tar.gz
Filename=${Name}_${Branch}.tar.gz
#echo ${Version} > Version.txt
#echo ${Tag} > Tag.txt
#echo ${Branch} > Branch.txt

rm -f ${Filename}

# warning composer.json is needed by symfony for getProjectDir() https://symfony.com/blog/new-in-symfony-3-3-a-simpler-way-to-get-the-project-root-directory
#   *.json \

rm -rf \
   var/logs/* \
   var/cache/* \
   web/media/* \
   *.log \
   *.nbr \
   *.yml \
   *.xml \
   *.js \
   *.lock \
   *.dist

# warning ! can't use  --exclude=*.dist with tar as it does not take care of path (all file in the tree are not included like for example app/config/parameters.yml.dist
# gen archive --transform='s|\./|./'${Tag}'/|g'
tar --exclude=build \
    --exclude=bin/git-scripts \
    --exclude=doc \
    --exclude=etc \
    -czhf ${Filename} ./*

sha256sum ${Filename} > ${Filename}.sha256.txt
