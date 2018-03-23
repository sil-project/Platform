#!/usr/bin/env bash
set -ex

mkdir --parents "${HOME}/bin"

composer self-update --no-progress --stable --no-interaction
composer -V
