#!/usr/bin/env bash
set -ex

Name=Platform

Version=$(git describe --tags)
Tag=$(git describe --tags --abbrev=0)
Filename=${Name}_${Version}.tar.gz
Pkgdir=package
rm -f ${Filename}

# gen archive --transform='s|\./|./'${Tag}'/|g'
tar -czf ${Filename} ./*
mkdir -p ${Pkgdir}
mv ${Filename} ${Pkgdir}

# gen deploy meta
rm -f ${Pkgdir}/env.to.deploy
touch ${Pkgdir}/env.to.deploy

echo "Name=${Name}" >> ${Pkgdir}/env.to.deploy
echo "Tag=${Tag}" >> ${Pkgdir}/env.to.deploy
echo "Version=${Version}" >>  ${Pkgdir}/env.to.deploy
echo "Filename=${Filename}" >> ${Pkgdir}/env.to.deploy

sha256sum ${Pkgdir}/${Filename} > ${Pkgdir}/sha256.to.deploy


if [ -f .env ]
then
    source .env
else
    echo "Please run this script from project root, and check .env file as it is mandatory"
    echo "If it is missing a quick solution is :"
    echo "ln -s .env.travis .env"
    exit 42
fi

# temp for deployement
# Todo : should handle database
# Maybe nslookup ip

psql -w -h ${DBHOST} -c  "update sil_ecommerce_channel set hostname='sandboxrd.libre-informatique.fr';" -U ${DBROOTUSER} -d ${DBAPPNAME}
