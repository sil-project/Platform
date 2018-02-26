#!/usr/bin/env bash
set -ex

Name=Platform

Version=$(git describe --tags)
Tag=$(git describe --tags --abbrev=0)
Filename=${Name}_${Version}.tar.gz
rm -f ${Filename}

# gen archive --transform='s|\./|./'${Tag}'/|g'
tar -czf ${Filename} ./*


ln -s  ${Filename} Latest.tar.gz

sha256sum ${Filename} > ${Filename}.sha256
