#!/bin/bash

branch_list="master wip-lisem wip-platform"
scrut_file="icmd/Quality.md"
cover_file="icmd/Coverage.md"
build_file="icmd/Build.md"

sep () {
    echo -n " | " 
}

table_init () {
   
    ##### Table header #####
    sep    
    echo -n "Name"
    sep
    for branch in ${branch_list}
    do
        echo -n ${branch}
        sep
    done
    echo

    ##### Table line #####
    sep
    echo -n "--"
    sep
    for branch in ${branch_list}
    do
        echo -n "--"
        sep
    done
    echo
    
}

repo_check ()
{
    curl -f "https://github.com/${account}/${repo}/tree/${branch}" > /dev/null 2>&1
}

repo_link () {
    branch=master
    repo_check
    if [ $? -eq 0 ]
       then
           echo -n "[${repo}](https://github.com/${account}/${repo})"   
    fi
    sep
}


scrut_link () {
    sep 
    repo_link 
    for branch in ${branch_list}
    do
        repo_check
        if [ $? -eq 0 ]
        then
            echo -n "[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/${account}/${repo}/badges/quality-score.png?b=${branch})](https://scrutinizer-ci.com/g/${account}/${repo}/?branch=${branch})"
        fi
        sep 
    done
    echo
}

travis_link () {
    sep 
    repo_link 
    for branch in ${branch_list}
    do
        repo_check
        if [ $? -eq 0 ]
        then
            echo -n "[![Build Status](https://travis-ci.org/${account}/${repo}.svg?branch=${branch})](https://travis-ci.org/${account}/${repo})"
        fi
        sep 
    done
    echo
}

cover_link () {
    sep 
    repo_link 
    for branch in ${branch_list}
    do
        repo_check
        if [ $? -eq 0 ]
        then
            echo -n " [![Coverage Status](https://coveralls.io/repos/github/${account}/${repo}/badge.svg?branch=${branch})](https://coveralls.io/github/${account}/${repo}?branch=${branch}) "
        fi
        sep 
    done
    echo
}


echo "## Scrutinizer #" > ${scrut_file}

echo "## Travis #" > ${build_file}

echo "## Coveralls #" > ${cover_file}


for search_dir in $@
do

    echo  >> ${scrut_file}
    echo "### "$(basename ${search_dir})" #"  >> ${scrut_file}
    table_init >> ${scrut_file}

    echo  >> ${build_file}
    echo "### "$(basename ${search_dir})" #"  >> ${build_file}
    table_init >> ${build_file}

    echo  >> ${cover_file}
    echo "### "$(basename ${search_dir})" #" >> ${cover_file}
    table_init >> ${cover_file}

    
    for ff  in $(find ${search_dir} -maxdepth 2 -name 'composer.json' |sort -u)
    do
        fd=$(dirname ${ff})
        repo=$(basename ${fd}) 
        account=$(basename $(dirname ${fd})) 

        scrut_link >> ${scrut_file}
        travis_link >> ${build_file}
        cover_link >> ${cover_file}

        
    done
done




