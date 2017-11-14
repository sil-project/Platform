#!/usr/bin/env sh

rm -f composer.lock

composer validate --no-check-lock
