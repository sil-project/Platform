#!/usr/bin/env bash
set -ev

#-d _build/doctrees


sphinx-build -b html doc/fr build/sphinx

#cd doc/en
#sphinx-build -b html -d _build/doctrees . _build/html
cd -
# bin/ci-scripts/do_it_for_bundle.sh run docs
