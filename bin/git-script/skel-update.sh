#!/usr/bin/env bash
set -e


# Component !

for i in src/*/Component/*/
do
    cp src/Skeleton/phpunit.component.xml.dist $i/phpunit.xml.dist
    git add $i/phpunit.xml.dist
done

# Bundle
for i in src/*/Bundle/*/
do
    cp src/Skeleton/phpunit.bundle.xml.dist $i/phpunit.xml.dist
    git add $i/phpunit.xml.dist

    mkdir -p $i/Tests/Functional
    touch $i/Tests/Functional/.gitkeep
    git add $i/Tests/Functional/.gitkeep


    cp -rp src/Skeleton/Tests/Resources/ $i/Tests/
    if [ -f  $i/Tests/Resources/App/AppKernel.php ]
    then
        rm -f $i/Tests/Resources/App/AppKernel.Example.php
    fi
    git add $i/Tests/Resources/
done

# Bundle & Component

for i in src/*/Bundle/*/ src/*/Component/*/
do
    mkdir -p $i/Tests/Unit/
    touch $i/Tests/Unit/.gitkeep
    git add $i/Tests/Unit/.gitkeep

    mkdir -p $i/bin/ci-scripts/
    for j in src/Skeleton/bin/ci-scripts/*.sh
    do
        b=$(basename $j)
        if [ ! -x $i/bin/ci-scripts/$b ]
        then
            cp $j $i/bin/ci-scripts/$b
            chmod 755 $i/bin/ci-scripts/$b
            git add $i/bin/ci-scripts/$b
        fi
    done

    mkdir -p $i/etc
    for j in src/Skeleton/etc/*
    do
        b=$(basename $j)
        if [ ! -f $i/etc/$b ]
        then
            cp $j $i/etc/$b
            git add $i/etc/$b
        fi
    done

    mkdir -p $i/.github
    for j in src/Skeleton/.github/*
    do
        b=$(basename $j)
        if [ ! -f $i/.github/$b ]
        then
            cp $j $i/.github/$b
            git add $i/.github/$b
        fi
    done

    # not all as there is *bundle* and *component*
    for j in src/Skeleton/.* src/Skeleton/LICENCE.md src/Skeleton/phpmd.xml.dist
    do
        if [ -f $j ]
        then
            b=$(basename $j)
            if [ ! -f $i/$b ]
            then
                cp $j $i/$b
                git add $i/$b
            fi
        fi

    done


done
