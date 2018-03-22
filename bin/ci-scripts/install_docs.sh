#!/usr/bin/env bash
set -ex

# need root access
#pip install --upgrade pip

#pip install --install-option="--install-scripts=$HOME/bin" -r doc/fr/requirements.txt --user

pip install --upgrade -r doc/requirements.txt --user

# bin/ci-scripts/do_it_for_bundle.sh install docs
