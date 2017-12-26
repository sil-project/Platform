#!/usr/bin/env bash

git config branch.master.rebase false
git subrepo push --all -p '[CI]'

