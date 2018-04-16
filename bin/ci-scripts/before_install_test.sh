#!/usr/bin/env bash
set -ex

mkdir --parents "${HOME}/bin"

# need root access
# composer self-update --no-progress --stable --no-interaction
composer -V
