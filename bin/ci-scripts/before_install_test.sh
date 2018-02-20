#!/usr/bin/env bash
set -ex

mkdir --parents "${HOME}/bin"

composer self-update --no-progress --stable --no-ansi --no-interaction
composer -V --no-ansi
