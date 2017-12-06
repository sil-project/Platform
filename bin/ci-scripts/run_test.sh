#!/usr/bin/env sh

if [ -n $PHPUNITCMD ]
then
    $PHPUNITCMD
fi


#bin/ci-scripts/do_it_for_bundle.sh run test

if [ -n $CODECEPTCMD ]
then
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
        $CODECEPTCMD -g $i --env=$CODECEPTENV
    done
    
    
    # check output
    #NBFAIL=$(find $OUTPUTDIR |grep fail|wc -w)
    #exit $NBFAIL;
fi 
