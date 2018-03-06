#!/usr/bin/env bash
set -ex

git checkout master
git pull
git config branch.master.rebase false

# Check if .gitrepo file reference the good remote
for i in $(find src -name .gitrepo)
do
    grep $(basename $(dirname $i)) $i > /dev/null
    if [ $? -ne 0 ]
    then
        echo "Bad: $i "
        exit 42
    fi
done


save_ref=$(git rev-parse HEAD)

git subrepo clean --all

git subrepo push --all -p '[CI]'

git reset --soft $save_ref
for i in $(find . -name '.gitrepo' -exec dirname {} \;)
do
    cd $i
    pwd
    git status -s .gitrepo
    # find if file have been modified as we don't want to update unpushed subrepo
    gst=$(git status -s .gitrepo | grep 'M' | wc -l)
    if [ $gst -eq 1 ]
       then
           git config --file=".gitrepo" subrepo.parent $save_ref
           git add .gitrepo
    fi
    cd -
done


git commit -m '[CI] Push All Subrepo'
