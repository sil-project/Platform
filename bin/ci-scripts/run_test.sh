#!/usr/bin/env sh

if [ $# -eq 0 ]
then
    bin/phpunit -v -c phpunit.xml.dist --coverage-clover build/logs/clover.xml
fi


#bin/ci-scripts/do_it_for_bundle.sh run test


OUTPUTDIR=$CODECEPTOUTPUT

# clean output
rm -rf $OUTPUTDIR/*.png
rm -rf $OUTPUTDIR/*.html


CODECEPTGROUP=$@
if [ $# -eq 0 ]
then
   CODECEPTGROUP="login menu" #login menu user crm variety seedbatch ecommerce" # all"
fi



for i in $CODECEPTGROUP
do
    $CODECEPTCMD -g $i --env=$CODECEPT_ENV
done


# check output
NBFAIL=$(find $OUTPUTDIR |grep fail|wc -w)
exit $NBFAIL;
