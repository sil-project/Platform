#!/usr/bin/env bash

cd src/doc/fr
sphinx-build -b html -d _build/doctrees . _build/html
cd -
cd src/doc/en
sphinx-build -b html -d _build/doctrees . _build/html
cd -
# bin/ci-scripts/do_it_for_bundle.sh run docs
