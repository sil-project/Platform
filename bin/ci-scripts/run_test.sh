#!/usr/bin/env sh


export SYMFONY_DEPRECATIONS_HELPER=weak

if [ $# -eq 0 ]
then
    bin/phpunit -v -c phpunit.xml --coverage-clover build/logs/clover.xml
fi



#bin/ci-scripts/do_it_for_bundle.sh run test


OUTPUTDIR=src/Tests/_output

# clean output
rm -rf $OUTPUTDIR/*.png
rm -rf $OUTPUTDIR/*.html

CODECEPTCMD="bin/codecept run Lisem -d --steps --fail-fast --no-interaction "


CODECEPTGROUP=$@
if [ $# -eq 0 ]
then
   CODECEPTGROUP="login menu crm" #login menu user crm variety seedbatch ecommerce" # all"
fi



for i in $CODECEPTGROUP
do
    $CODECEPTCMD -g $i --env=firefox
    #$CODECEPTCMD -g $i --env=chrome
done


# check output
NBFAIL=$(find $OUTPUTDIR |grep fail|wc -w)
exit $NBFAIL;
