#!/usr/bin/env bash

cd src/PlatformBundle/Resources/doc
sphinx-build -b html -d _build/doctrees . _build/html
cd -

# bin/ci-scripts/do_it_for_bundle.sh run docs
