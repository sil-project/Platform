#!/usr/bin/env bash
set -ex

git checkout master
git pull
git config branch.master.rebase false

save_ref=$(git rev-parse HEAD)

git subrepo push --all -p '[CI]'

git reset --soft $save_ref
for i in $(find . -name '.gitrepo' -exec dirname {} \;)
do
    cd $i

    # find if file have been modified as we don't want to update unpushed subrepo
    gst=$(git status -s $i | grep 'M' | grep '.gitrepo' | wc -l)
    if [ $gst -eq 1 ]
       then
           git config --file=".gitrepo" subrepo.parent $save_ref
           git add .gitrepo
    fi
    cd -
done


git commit -m '[CI] Push All Subrepo'
