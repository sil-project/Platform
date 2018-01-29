#!/usr/bin/env bash

cd doc/fr
sphinx-build -b html -d _build/doctrees . _build/html
cd -
cd doc/en
sphinx-build -b html -d _build/doctrees . _build/html
cd -
# bin/ci-scripts/do_it_for_bundle.sh run docs
