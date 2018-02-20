#!/usr/bin/env bash
set -ex

Name=Platform

Version=$(git describe --tags)
Tag=$(git describe --tags --abbrev=0)
Filename=${Name}_${Version}.tar.gz
Pkgdir=package
rm -f ${Filename}

# gen archive --transform='s|\./|./'${Tag}'/|g'
tar -czf ${Pkgdir}/${Filename} ./*


# gen deploy meta
mkdir -p ${Pkgdir}
rm -f ${Pkgdir}/env.to.deploy
touch ${Pkgdir}/env.to.deploy

echo "Name=${Name}" >> ${Pkgdir}/env.to.deploy
echo "Tag=${Tag}" >> ${Pkgdir}/env.to.deploy
echo "Version=${Version}" >>  ${Pkgdir}/env.to.deploy
echo "Filename=${Filename}" >> ${Pkgdir}/env.to.deploy

sha256sum ${Filename} > ${Pkgdir}/sha256.to.deploy
cp -rp bin/deploy-scripts/deploy_archive.sh ${Pkgdir}/
