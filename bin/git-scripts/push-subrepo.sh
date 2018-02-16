#!/usr/bin/env bash
set -ex

git checkout master
git pull
git config branch.master.rebase false
git subrepo push --all -p '[CI]'
