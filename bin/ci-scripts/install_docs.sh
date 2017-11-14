#!/usr/bin/env sh
set -ev

pip install -r src/AppBundle/Resources/doc/requirements.txt --user

bin/ci-scripts/do_it_for_bundle.sh install docs
