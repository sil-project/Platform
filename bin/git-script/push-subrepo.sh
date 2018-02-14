#!/usr/bin/env bash
set -ev

git checkout master
git pull
git config branch.master.rebase false
git subrepo push --all -p '[CI]'
