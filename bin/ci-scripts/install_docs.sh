#!/usr/bin/env bash
set -ev

pip install -r src/PlatformBundle/Resources/doc/requirements.txt --user

# bin/ci-scripts/do_it_for_bundle.sh install docs
