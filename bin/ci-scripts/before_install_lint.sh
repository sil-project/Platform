#!/usr/bin/env bash
set -ex

mkdir --parents "${HOME}/bin"

#composer self-update 1.5.6 --no-progress --stable
composer self-update --no-progress --stable
