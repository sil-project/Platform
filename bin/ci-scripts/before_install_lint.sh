#!/usr/bin/env bash
set -ev

mkdir --parents "${HOME}/bin"

composer self-update 1.5.6 --no-progress --stable
