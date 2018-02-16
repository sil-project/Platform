#!/usr/bin/env bash
set -ex

#-d _build/doctrees


sphinx-build -b html doc/fr build/sphinx

#cd doc/en
#sphinx-build -b html -d _build/doctrees . _build/html

# bin/ci-scripts/do_it_for_bundle.sh run docs
