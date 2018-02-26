#!/usr/bin/env bash
set -ex

Name=Platform

Version=$(git describe --tags)
Tag=$(git describe --tags --abbrev=0)
Filename=${Name}_${Version}.tar.gz
echo ${Version} > Version.txt
echo ${Tag} > Tag.txt

rm -f ${Filename}

# gen archive --transform='s|\./|./'${Tag}'/|g'
tar -czf ${Filename} ./*

sha256sum ${Filename} > ${Filename}.sha256
