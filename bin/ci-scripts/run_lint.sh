#!/usr/bin/env bash

rm -f composer.lock

composer validate --no-check-lock
